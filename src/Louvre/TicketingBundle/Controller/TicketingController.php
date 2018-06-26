<?php

namespace Louvre\TicketingBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;

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

              $bookingAction = $this->container->get('louvre_ticketing.booking');
              $bookingAction = $bookingAction->bookingAction($session, $reservation);

              if($bookingAction == 'ok')
              {
                return $this->redirectToRoute('booking_prepare');
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

    public function checkoutAction(Request $request)
    { 
        if ($request->isMethod('POST'))
        {
          // Récupération de la session
          $session = $request->getSession();

          $checkoutAction = $this->container->get('louvre_ticketing.checkout');
          $checkoutAction = $checkoutAction->checkoutAction($session);

          if( $checkoutAction == 'ok')
          {
              $session->getFlashBag()->add("success","Votre réservation à été effectuée, vous allez recevoir un mail de confirmation dans les prochaines minutes");

              return $this->redirectToRoute("booking_thanks");
          }
        }
        else 
        {
          return $this->redirectToRoute("booking_prepare"); 
        }
    }

    public function thanksAction()
    {
        return $this->render('LouvreTicketingBundle:Ticketing:thanks.html.twig');
    }

    public function informationsAction()
    {
          return $this->render('LouvreTicketingBundle:Ticketing:informations.html.twig');
    }

}