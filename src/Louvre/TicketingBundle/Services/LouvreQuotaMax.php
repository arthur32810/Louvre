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

  public function quotaMax($visitDay, $nbillet)
  {
  	$request = $this->requestStack->getCurrentRequest();

  	// Récupération du quota
    $quotaMax = $this->quotaMax;

    $request = $this->requestStack->getCurrentRequest();

    //récupération du jour de visite
   	$visitDay = new DateTime($visitDay->format('m/d/Y'));

    // on récupére le nombre de réservation pour le jour $visitDay
    $nReservation = $this->em
      ->getRepository('LouvreTicketingBundle:Billet')
      ->nReservation($visitDay);

    
    	$nReservation = $nReservation + $nbillet;

	    //On regarde le nombre de réservation, s'il y a plus de 1000 places -> erreur
	    if( $nReservation > $quotaMax ) 
	    { 
	    	return 'moreQuotaMax';
	    }
      else { return; }

  }

}