<?php

namespace Louvre\TicketingBundle\Services;

use Louvre\TicketingBundle\Services\Stripe;
use Louvre\TicketingBundle\Services\LouvreCodeReservation;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

use Dompdf\Dompdf;
use Dompdf\Options;

class CheckoutAction
{
	private $stripeService;
	private $LouvreCodeReservation;
	private $twig;
	private $mailer;

	public function __construct(Stripe $stripeService, LouvreCodeReservation $louvreCodeReservation, EntityManagerInterface $em, $twig, $mailer)
	{
		$this->stripeService 			= $stripeService;
		$this->louvreCodeReservation 	= $louvreCodeReservation;
		$this->em 						= $em;
		$this->twig 					= $twig;
		$this->mailer 					= $mailer;
	}
	public function checkoutAction($session)
	{

          	// paiement stripe
            //$stripe = $this->stripeService->stripe();
			$stripe = 'success';

            //paiement réussi
            if($stripe == 'success')
            {
              	//Envoi des infos en BDD                 
                $reservation = $session->get('billets');
                $billets = $reservation->getBillet();

                //Création du code de réservation
                $code = $this->louvreCodeReservation->code(10);

                $reservation = $session->get('billets');

                $reservation->setEmail($_POST['stripeEmail']);
                $reservation->setReservationCode($code);

                $visitDay = $reservation->getDay();

                $billets = $reservation->getBillet();

                foreach ($billets as $billet) {
                  $billet->setReservation($reservation);
                }

                // Étape 1 : On « persiste » l'entité
                $this->em->persist($reservation);

                foreach ($billets as $billet) {
                  $billet->setVisitDay($visitDay);
                  $this->em->persist($billet);
                }

                 $this->em->flush();

              //-----------------------

              // PDF

              $options = new Options();

              $options->set('isRemoteEnabled', TRUE);

              $dompdf = new Dompdf($options);

              $html = $this->twig->render('LouvreTicketingBundle:Ticketing:billet.html.twig', array("reservation"=> $reservation, "billets" => $billets, "code" => $code));

              $dompdf->loadHtml($html);

              $dompdf->render();

              $billetPdf = $dompdf->output();

              //----------------------

              // Mail

              // Création du mail
              $message = (new \Swift_Message('Votre réseravtion pour le '.$reservation->getDay()->format('d/m/Y')))
                ->setFrom('billetterie@louvre.fr')
                ->setTo($_POST['stripeEmail'])
                ->setBody(
                        $this->twig->render(
                          'LouvreTicketingBundle:Ticketing:billet.html.twig', array("reservation"=> $reservation, "billets" => $billets, "code" => $code)), 'text/html')
                ->attach(new \Swift_Attachment($billetPdf, 'billet.pdf'));

              //Envoi du mail
              $this->mailer->send($message);

              //--------------------------------------------


                return 'ok';
            }
            // erreur sur le paiement
            elseif($stripe == 'error')
            {
                $this->addFlash("error","Une erreur est intervenue durant le paiement, veuillez réessayer");
                
                return $this->redirectToRoute("booking_prepare");
            }
       
        
	}
}