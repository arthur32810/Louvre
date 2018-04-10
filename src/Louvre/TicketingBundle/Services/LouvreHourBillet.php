<?php

namespace Louvre\TicketingBundle\Services;

use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;

class LouvreHourBillet
{
	private $requestStack;

	public function __construct(RequestStack $requestStack)
	{
	  	$this->requestStack = $requestStack;
	}

	public function hourBillet($reservation, $billets)
	{
		$request = $this->requestStack->getCurrentRequest();

		//Récupération du jour et du jour de visite
		$day = new DateTime(date('m/d/Y'));
	  	$visitday = new DateTime($reservation->getDay()->format('m/d/Y'));

	  	//Défintion d'un compteur pour les billet
	  	$i=0;

	  	//Défintion de la variable retour
	  	$return = 'ok';

	  	foreach ($billets as $billet) {
	  		if($visitday == $day)
			{
				$i++;
				
				//Récupération du type de billet
				$duration = $billet->getDuration();
				
				// Récupération de l'heure 
				date_default_timezone_set('Europe/Paris');
				$hour = date('H');

				// Test pour le billet
				if($hour >= 14 && $duration == 1)
				{	
					//Définition de la session
					$session = $request->getSession();

					//Message d'erreur
					$session->getFlashBag()->add('hourBillet', 'Votre billet '.$i.' n\'est pas valide. Vous ne pouvez commandé de billet "journée" au-dessus de 14 heures.');

					$return = 'notHourBillet';

				}

			}
	  	}
	  	return $return;
		
	}
}