<?php

namespace tests\Louvre\TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use DateTime;

class TicketingControllerTest extends WebTestCase
{
	public function testHomepageIsUp()
	{
		$client = static::createClient();
		$client->request('GET', '/');

		$this->assertSame(200, $client->getResponse()->getStatusCode());
	}

	public function testPageBookingIsUp()
	{
		$client = static::createClient();
		$client->request('GET', '/booking');

		$this->assertSame(200, $client->getResponse()->getStatusCode());
	}

	public function testPageInformationsPratiquesIsUp()
	{
		$client = static::createClient();
		$client->request('GET', '/informations-pratiques');

		$this->assertSame(200, $client->getResponse()->getStatusCode());
	}

	public function testBookingPage()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/booking');

		$this->assertSame(1, $crawler->filter('html:contains("Billetterie")')->count());
	}

	public function testAddReservationNoBillet()
	{
		$client = static::createClient();
		$client->followRedirects();

		$crawler = $client->request('GET', '/');

		$link = $crawler->selectLink('Billetterie')->link();
		$crawler = $client->click($link);

		$form = $crawler->selectButton('Commander')->form();
		$form['louvre_ticketingbundle_reservation[day]'] = '2018-11-07';
		
		$crawler = $client->submit($form);

		$this->assertSame(1, $crawler->filter('p.alert.alert-danger')->count());
	}
}