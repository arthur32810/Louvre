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

	public function testnotHourBillet()
	{
		$requestStack = new RequestStack();

		$hour = new LouvreHourBillet($requestStack);

		$date = new DateTime();

		$this->assertSame( 'notHourBillet', $hour->hourBillet($date, 1) );
	}
}