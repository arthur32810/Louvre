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

use Dompdf\Dompdf;
use Dompdf\Options;


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

             if (count($billets) > 0)
             {
                // Récupération du jour de visite
                $visitDay = $reservation->getDay();

                // Test si un billet est acheté le même jour au dessus de 14h
                foreach ($billets as $nbillet => $billet) {

                  // Appel du service 
                  $hourBillet = $this->container->get('louvre_ticketing.hourBillet');

                  //Récupération du type de billet
                  $duration = $billet->getDuration();

                  $hourBillet = $hourBillet->hourBillet($visitDay, $duration);

                  if($hourBillet == 'notHourBillet')
                  {
                    $session->getFlashBag()->add('hourBillet', 'Votre billet '.($nbillet+1).' n\'est pas valide. Vous ne pouvez commandé de billet "journée" au-dessus de 14 heures.');

                    // Envoie vers la page de formulaire si non soumis
                    return $this->render('LouvreTicketingBundle:Ticketing:booking.html.twig', array(
                      'form' => $form->createView(),
                    ));
                  }
                }

                // Test du nombre de billet
                foreach ($billets as $nbillet => $billet) {

                    // Appel du service
                    $quotaMax = $this->container->get('louvre_ticketing.quotaMax');
                    $quotaMax = $quotaMax->quotaMax($visitDay, $nbillet);

                    if($quotaMax == 'moreQuotaMax')
                    {
                      //Message d'erreur
                      $session->getFlashBag()->add('quotaMax', 'Vous dépassé le nombre de visiteur du jour, vous ne pouvez pas réserver votre billet '.($nbillet+1).'Veuillez supprimer ce billet ou choisir une autre date de visite.');

                      // Envoie vers la page de formulaire si non soumis
                      return $this->render('LouvreTicketingBundle:Ticketing:booking.html.twig', array(
                        'form' => $form->createView(),
                      ));
                    }
                } 

                // Calcul du tarif
                foreach ($billets as $nbillet => $billet) {

                  // Appel du service
                  $price = $this->container->get('louvre_ticketing.price');

                  // Récupération de la valeur "réduction"
                  $reduction = $billet->getReduction();

                  // Récupération de la date de naissance
                  $birthday = $billet->getBirthday();

                  $price = $price->price($reduction, $birthday);

                  $billet->setPrice($price);
                }

                $billets = $session->set('billets', $reservation);

                // envoie vers la page récapitulative si formulaire soumis
                return $this->redirectToRoute('booking_prepare');      
              }

              else {
                      $session->getFlashBag()->add('0billet', 'Aucun billet n\'a été rempli.');
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
        if($_POST)
        {

          // paiement stripe
            $stripe = $this->container->get('louvre_ticketing.stripe');
            $stripe = $stripe->stripe();

            //paiement réussi
            if($stripe == 'success')
            {
              //Envoi des infos en BDD 

                // Récupération de la session
                $session = $request->getSession();
                $reservation = $session->get('billets');

                $billets = $reservation->getBillet();

                //Création du code de réservation
                $code = $this->container->get('louvre_ticketing.codeReservation');
                $code = $code->code(10);

                $session = $request->getSession();
                $reservation = $session->get('billets');

                $reservation->setEmail($_POST['stripeEmail']);
                $reservation->setReservationCode($code);

                $visitDay = $reservation->getDay();

                $billets = $reservation->getBillet();

                foreach ($billets as $billet) {
                  $billet->setReservation($reservation);
                }

                $em = $this->getDoctrine()->getManager();

                // Étape 1 : On « persiste » l'entité
                $em->persist($reservation);

                foreach ($billets as $billet) {
                  $billet->setVisitDay($visitDay);
                  $em->persist($billet);
                }

                 $em->flush();

              //-----------------------

              // PDF

              $options = new Options();

              $options->set('isRemoteEnabled', TRUE);

              $dompdf = new Dompdf($options);

              $html = $this->renderView('LouvreTicketingBundle:Ticketing:billet.html.twig', array("reservation"=> $reservation, "billets" => $billets, "code" => $code));

              $dompdf->loadHtml($html);

              $dompdf->render();

              $billetPdf = $dompdf->output();

              //----------------------

              // Mail 

              $mailer = $this->get('mailer');

              // Création du mail
              $message = (new \Swift_Message('Votre réseravtion pour le '.$reservation->getDay()->format('d/m/Y')))
                ->setFrom('billetterie@louvre.fr')
                ->setTo($_POST['stripeEmail'])
                ->setBody(
                        $this->renderView(
                          'LouvreTicketingBundle:Ticketing:billet.html.twig', array("reservation"=> $reservation, "billets" => $billets, "code" => $code)), 'text/html')
                ->attach(new \Swift_Attachment($billetPdf, 'billet.pdf'));

              //Envoi du mail
              $mailer->send($message);

              //--------------------------------------------


                $this->addFlash("success","Votre réservation à été effectué, vous allez recevoir un mail de confirmation dans les prochaines minutes");

                return $this->redirectToRoute("booking");
            }
            // erreur sur le paiement
            elseif($stripe == 'error')
            {
                $this->addFlash("error","Une erreur est intervenue durant le paiement, veuillez réessayer");
                
                return $this->redirectToRoute("booking_prepare");
            }
        }
        else 
        {
          return $this->redirectToRoute("booking"); 
        }
    }

    public function informationsAction()
    {
          return $this->render('LouvreTicketingBundle:Ticketing:informations.html.twig');
    }

}