<?php

namespace Louvre\TicketingBundle\Services;

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

  public function quotaMax($reservation)
  {
  	// Récupération du quota
    $quotaMax = $this->quotamax;

    $request = $this->requestStack->getCurrentRequest();

    // on récupére le nombre de réservation pour le jour $date
    $nReservation = $this->em
      ->getRepository('LouvreTicketingBundle:Billet')
      ->nReservation($reservation);
  
    if($nReservation>$quotaMax) { 
        //erreur
      }

  }

}