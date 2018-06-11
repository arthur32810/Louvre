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

<<<<<<< HEAD
	public function hourBillet($reservation, $billets)
=======
	public function hourBillet($visitday, $duration)
>>>>>>> tests
	{
		$request = $this->requestStack->getCurrentRequest();

		//Récupération du jour et du jour de visite
		$day = new DateTime(date('m/d/Y'));
<<<<<<< HEAD
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
=======
	  	$visitday = new DateTime($visitday->format('m/d/Y'));
	  	
  		if($visitday == $day)
		{							
			// Récupération de l'heure 
			date_default_timezone_set('Europe/Paris');
			$hour = date('H');

			// Test pour le billet journée
			if($hour >= 14 && $duration == 1)
			{	
				// Si l'heure est supérieure à 14 h et le type de billet est journée (1) => Erreur
				$return = 'notHourBillet';
				return $return;
			}
			else {return 'ok'; }

		}
		else { return 'ok'; }
>>>>>>> tests
		
	}
}