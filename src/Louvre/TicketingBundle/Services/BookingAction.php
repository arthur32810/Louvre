<?php

namespace Louvre\TicketingBundle\Services;

use Louvre\TicketingBundle\Services\LouvreHourBillet;
use Louvre\TicketingBundle\Services\LouvreQuotaMax;
use Louvre\TicketingBundle\Services\LouvrePrice;

class BookingAction
{
	private $louvreHourBillet;
	private $louvreQuotaMax;
	private $louvrePrice;

	public function __construct(LouvreHourBillet $louvreHourBillet, LouvreQuotaMax $louvreQuotaMax, LouvrePrice $louvrePrice)
	{
		$this->louvreHourBillet = $louvreHourBillet;
		$this->louvreQuotaMax 	= $louvreQuotaMax;
		$this->louvrePrice 		= $louvrePrice;
	}
	function bookingAction($session, $reservation)
	{
		$billets = $reservation->getBillet();

		if (count($billets) > 0)
        {
            // Récupération du jour de visite
            $visitDay = $reservation->getDay();

            // Test si un billet est acheté le même jour au dessus de 14h
            foreach ($billets as $nbillet => $billet) {

              //Récupération du type de billet
              $duration = $billet->getDuration();

              $hourBillet = $this->louvreHourBillet->hourBillet($visitDay, $duration);

              if($hourBillet == 'notHourBillet')
              {
                $session->getFlashBag()->add('hourBillet', 'Votre billet '.($nbillet+1).' n\'est pas valide. Vous ne pouvez commandé de billet "journée" au-dessus de 14 heures.');

                // Envoie vers la page de formulaire si non soumis
                return;
              }
            }

            // Test du nombre de billet
            foreach ($billets as $nbillet => $billet) {

                // Appel du service
                $quotaMax = $this->louvreQuotaMax->quotaMax($visitDay, $nbillet);

                if($quotaMax == 'moreQuotaMax')
                {
                  //Message d'erreur
                  $session->getFlashBag()->add('quotaMax', 'Vous dépassé le nombre de visiteur du jour, vous ne pouvez pas réserver votre billet '.($nbillet+1).'Veuillez supprimer ce billet ou choisir une autre date de visite.');

                  // Envoie vers la page de formulaire si non soumis
                  return;
                }
            } 

            // Calcul du tarif
            foreach ($billets as $nbillet => $billet) {

              // Récupération de la valeur "réduction"
              $reduction = $billet->getReduction();

              // Récupération de la date de naissance
              $birthday = $billet->getBirthday();

              $price = $this->louvrePrice->price($reduction, $birthday);

              $billet->setPrice($price);
            }

            $billets = $session->set('billets', $reservation);

            // envoie vers la page récapitulative si formulaire soumis
            return 'ok';  
      	}

      	else {
              $session->getFlashBag()->add('0billet', 'Aucun billet n\'a été rempli.');
      	}
    }
}