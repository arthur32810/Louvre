<?php

namespace Louvre\TicketingBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class LouvreQuotaMax
{
	private $requestStack;
	private $em;
	private $quotaMax;

	public function __construct(RequestStack $requestStack, EntityManagerInterface $em, $quotaMax)
  {
    $this->requestStack = $requestStack;
    $this->em           = $em;
    $this->quotaMax 	= $quotaMax;
  }

<<<<<<< HEAD
  public function quotaMax($reservation, $billets)
=======
  public function quotaMax($visitDay, $nbillet)
>>>>>>> tests
  {
  	$request = $this->requestStack->getCurrentRequest();

  	// Récupération du quota
    $quotaMax = $this->quotaMax;

    $request = $this->requestStack->getCurrentRequest();

    //récupération du jour de visite
<<<<<<< HEAD
   	$visitDay = new DateTime($reservation->getDay()->format('m/d/Y'));
=======
   	$visitDay = new DateTime($visitDay->format('m/d/Y'));
>>>>>>> tests

    // on récupére le nombre de réservation pour le jour $visitDay
    $nReservation = $this->em
      ->getRepository('LouvreTicketingBundle:Billet')
      ->nReservation($visitDay);

<<<<<<< HEAD
    //Définition du compteur
    $i = 0;

    // Défintion de la valeur de retour
    $return = 'ok';

    foreach ($billets as $billet) 
    {
    	$nReservation = $nReservation + $i;
=======
    
    	$nReservation = $nReservation + $nbillet;
>>>>>>> tests

	    //On regarde le nombre de réservation, s'il y a plus de 1000 places -> erreur
	    if( $nReservation > $quotaMax ) 
	    { 
<<<<<<< HEAD
	    	//Définition de la session
			$session = $request->getSession();

	    	//Message d'erreur
			$session->getFlashBag()->add('quotaMax', 'Vous dépassé le nombre de visiteur du jour, vous ne pouvez pas réserver votre billet '.($i+1));

	        $return = 'moreQuotaMax';
	    }

	    $i++;
	}

	return $return;
=======
	    	return 'moreQuotaMax';
	    }
      else { return; }
>>>>>>> tests

  }

}