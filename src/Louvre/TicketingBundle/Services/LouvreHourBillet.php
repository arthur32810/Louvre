<?php

namespace Louvre\TicketingBundle\Services;

use DateTime;

class LouvreHourBillet
{
	public function hourBillet($reservation, $billets)
	{
		$day = new DateTime(date('m/d/Y'));
	  	$visitday = new DateTime($reservation->getDay()->format('m/d/Y'));

	  	foreach ($billets as $billet) {
	  		if($visitday == $day)
			{
			 	echo "même jour";
			}
			else { echo "autre jour";}
	  	}
		
	}
}