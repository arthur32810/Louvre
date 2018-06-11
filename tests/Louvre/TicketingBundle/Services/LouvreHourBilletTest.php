<?php

namespace tests\Louvre\TicketingBundle\Services;

use Louvre\TicketingBundle\Services\LouvreHourBillet;
use Symfony\Component\HttpFoundation\RequestStack;
use DateTime;


use PHPUnit\Framework\TestCase;

class LouvreHourBilletTest extends TestCase
{
	public function testHourBillet()
	{
		$requestStack = new RequestStack();

		$hour = new LouvreHourBillet($requestStack);

		$date = DateTime::createFromFormat('Y-m-d', '2018-11-07');

		$this->assertSame( 'ok', $hour->hourBillet($date, 1) );
	}

	public function testNotHourBillet()
	{
		// Récupération de l'heure 
		date_default_timezone_set('Europe/Paris');
		$hour = date('H');

		if ($hour >= 14)
		{
			$requestStack = new RequestStack();

			$hour = new LouvreHourBillet($requestStack);

			$date = new DateTime();

			$this->assertSame( 'notHourBillet', $hour->hourBillet($date, 1) );
		}
		else
		{
			$this->markTestSkipped( 'Heure inférierure à 14h.' );
        }
		
	}
}