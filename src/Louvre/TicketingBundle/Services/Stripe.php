<?php

namespace Louvre\TicketingBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class Stripe
{
	private $requestStack;
	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}

	public function stripe()
	{
		$request = $this->requestStack->getCurrentRequest();

		\Stripe\Stripe::setApiKey("sk_test_GyKdvxgMo9I1HSjAzeHsdeZp");


		  // Get the credit card details submitted by the form
		  $token = $request->request->get('stripeToken');

		  // récupération du total 
		  $total = intval($request->request->get('total').'00');

		  // Create a charge: this will charge the user's card
		  try {
		      $charge = \Stripe\Charge::create(array(
		          "amount" => $total, // Amount in cents
		          "currency" => "eur",
		          "source" => $token,
		          "description" => "Paiement Stripe - Musée du louvre"
		      ));

		      return 'success';

		     
		  } 

		  catch(\Stripe\Error\Card $e) {
		  	return 'error';
		  }
	}
}