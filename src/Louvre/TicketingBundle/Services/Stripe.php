<?php

namespace Louvre\TicketingBundle\Services;

class Stripe
{
	public function stripe()
	{
		\Stripe\Stripe::setApiKey("sk_test_GyKdvxgMo9I1HSjAzeHsdeZp");


		  // Get the credit card details submitted by the form
		  $token = $_POST['stripeToken'];

		  // récupération du total 
		  $total = intval($_POST['total'].'00');

		  // Create a charge: this will charge the user's card
		  try {
		      $charge = \Stripe\Charge::create(array(
		          "amount" => $total, // Amount in cents
		          "currency" => "eur",
		          "source" => $token,
		          "description" => "Paiement Stripe - OpenClassrooms Exemple"
		      ));

		      return 'success';

		     
		  } 

		  catch(\Stripe\Error\Card $e) {
		  	return 'error';
		  }
	}
}