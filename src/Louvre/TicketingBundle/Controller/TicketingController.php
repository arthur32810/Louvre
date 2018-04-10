<?php

namespace Louvre\TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Louvre\TicketingBundle\Entity\Reservation;
use Louvre\TicketingBundle\Entity\Billet;
use Louvre\TicketingBundle\Form\ReservationType;
use Louvre\TicketingBundle\Form\BilletType;


class TicketingController extends Controller
{

    public function indexAction()
    {
         return $this->render('LouvreTicketingBundle:Ticketing:index.html.twig');
    }

    public function bookingAction(Request $request)
    {
        // Création de la variable réservation avec l'entité réservation
        $reservation = new Reservation();
        
        // Création du formulaire
        $form = $this->createForm(ReservationType::class, $reservation);
        // Test si soumission du formulaire
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
              // Récupération de la session
              $session = $request->getSession();

              $billets = $reservation->getBillet();

              // Appel du service pour tester si le billet et acheté la même jour au dessus de 14h
              $hourBillet = $this->container->get('louvre_ticketing.hourBillet');
              $hourBillet = $hourBillet->hourBillet($reservation, $billets);

              if($hourBillet != 'notHourBillet')
              {
                  $quotaMax = $this->container->get('louvre_ticketing.quotaMax');
                  $quotaMax = $quotaMax->quotaMax($reservation, $billets);

                  if($quotaMax != 'moreQuotaMax')
                  {
                      $price = $this->container->get('louvre_ticketing.price');
                      $price = $price->price($billets);
                      
                      $billets = $session->set('billets', $reservation);
                      // envoie vers la page récapitulative si formulaire soumis
                      return $this->redirectToRoute('booking_prepare');
                  }
              }
        }
        // Envoie vers la page de formulaire si non soumis
        return $this->render('LouvreTicketingBundle:Ticketing:booking.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    public function prepareAction(Request $request)
    {
    	  // Récupération de la session
        $session = $request->getSession();
        $reservation = $session->get('billets');

        $billets = $reservation->getBillet();

        return $this->render('LouvreTicketingBundle:Ticketing:prepare.html.twig', array("reservation" => $reservation, "billets" => $billets));
    }

    public function informationsAction()
    {
          return $this->render('LouvreTicketingBundle:Ticketing:informations.html.twig');
    }
}