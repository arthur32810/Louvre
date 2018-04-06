<?php

namespace Louvre\TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketingController extends Controller
{

    public function indexAction()
    {
         return $this->render('LouvreTicketingBundle:Ticketing:index.html.twig');
    }

    public function bookingAction()
    {
          
    }

    public function prepareAction()
    {
    	
    }

    public function informationsAction()
    {
          return $this->render('LouvreTicketingBundle:Ticketing:informations.html.twig');
    }
}