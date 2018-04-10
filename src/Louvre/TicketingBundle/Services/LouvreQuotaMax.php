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

  public function quotaMax($reservation, $billets)
  {
  	// Récupération du quota
    $quotaMax = $this->quotaMax;

    $request = $this->requestStack->getCurrentRequest();

    //récupération du jour de visite
   	$visitDay = new DateTime($reservation->getDay()->format('m/d/Y'));

    // on récupére le nombre de réservation pour le jour $visitDay
    $nReservation = $this->em
      ->getRepository('LouvreTicketingBundle:Billet')
      ->nReservation($visitDay);

    //Définition du compteur
    $i = 0;

    // Défintion de la valeur de retour
    $return = 'ok';

    foreach ($billets as $billet) 
    {
    	$nReservation = $nReservation + $i;

	    //On regarde le nombre de réservation, s'il y a plus de 1000 places -> erreur
	    if( $nReservation > $quotaMax ) 
	    { 
	        $return = 'moreQuotaMax';
	    }

	    $i++;
	}

	return $return;

  }

}