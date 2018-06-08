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

	public function hourBillet($visitday, $duration)
	{
		$request = $this->requestStack->getCurrentRequest();

		//Récupération du jour et du jour de visite
		$day = new DateTime(date('m/d/Y'));
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

		}
		else { return; }
		
	}
}