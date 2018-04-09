<?php

namespace Louvre\TicketingBundle\Services;

class LouvreHourBillet
{
	public function hourBillet($reservation)
	{
		$day = new DateTime(date('m/d/Y'));
	  	$visitday = new DateTime($reservation->getDay()->format('m/d/Y'));
	 
		if($visitday == $day)
		{
		 	echo "même jour";
		}
		else { echo "autre jour";}
	}
}