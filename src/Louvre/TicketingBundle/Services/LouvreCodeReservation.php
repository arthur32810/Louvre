<?php

namespace Louvre\TicketingBundle\Services;

class LouvreCodeReservation
{
	function code($car) {

    $code = "";
    $chaine = "abcdefghijklmnpqrstuvwxy";
    $chaine .= strtoupper($chaine);
    $chaine .= "0123456789";

    for($i=0; $i<$car; $i++) {
      $code .= $chaine[rand()%strlen($chaine)];
    }

    return $code;
  }
}