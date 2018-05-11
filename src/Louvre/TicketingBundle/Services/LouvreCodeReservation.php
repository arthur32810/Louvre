<?php

namespace Louvre\TicketingBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class LouvreCodeReservation
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em           = $em;
  }

	function code($car) {

    $unique_code ='';

    while($unique_code != 'yes')
    {

      $code = "";
      $chaine = "abcdefghijklmnpqrstuvwxy";
      $chaine .= strtoupper($chaine);
      $chaine .= "0123456789";

      for($i=0; $i<$car; $i++) {
        $code .= $chaine[rand()%strlen($chaine)];
      }

      $exist_code = $this->em
      ->getRepository('LouvreTicketingBundle:Reservation')
      ->findByReservationCode($code);

      $exist_code = count($exist_code);

      if($exist_code > 0)
      {
        $unique_code='no';
      }
      else
      {
        $unique_code='yes';
      }

    }

    return $code;
  }
}