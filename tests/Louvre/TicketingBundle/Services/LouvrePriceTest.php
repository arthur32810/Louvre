<?php

namespace tests\Louvre\TicketingBundle\Services;

use Louvre\TicketingBundle\Services\LouvrePrice;
use DateTime;
use DateInterval;

use PHPUnit\Framework\TestCase;

Class LouvrePriceTest extends TestCase
{
	private $price;
	private $birthday;

	public function setup()
	{
		$this->price = new LouvrePrice(16, 8, 12, 10);

		$this->birthday = new DateTime;

	}

	public function testReductionIsTrue()
	{	
		$this->assertSame(10, $this->price->price(true, $this->birthday));
	}


	public function testPriceIs0()
	{
		// Age 4 ans
		$this->birthday->sub(new DateInterval('P2Y'));

		$this->assertSame(0, $this->price->price(false, $this->birthday));
	}

	public function testChildrenPrice()
	{
		//Age 8 ans
		$this->birthday->sub(new DateInterval('P8Y'));

		$this->assertSame(8, $this->price->price(false, $this->birthday));
	}

	public function testNormalPrice()
	{
		//Age 50 ans
		$this->birthday->sub(new DateInterval('P50Y'));

		$this->assertSame(16, $this->price->price(false, $this->birthday));
	}

	public function testSeniorPrice()
	{
		//Age 65 ans
		$this->birthday->sub(new DateInterval('P65Y'));

		$this->assertSame(12, $this->price->price(false, $this->birthday));
	}

	public function testExcption()
	{

		$this->expectException('LogicException');

		$this->price->price(false, $this->birthday);
	}

	public function tearDown()
	{
		$this->price = null;
		$this->birthday = null;
	}
}