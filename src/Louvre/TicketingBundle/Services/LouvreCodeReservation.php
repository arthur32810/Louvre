<?php

namespace Louvre\TicketingBundle\CodeReservation;

class LouvreCodeReservation
{
	function random($car) {

    $code = "";
    $chaine = "abcdefghijklmnpqrstuvwxy";
    $chaine .= strtoupper($chaine);
    $chaine .= "0123456789";

    for($i=0; $i<$car; $i++) {
      $code .= $chaine[rand()%strlen($chaine)];
    }

    return $code;
  }

    // APPEL
    // Génère une chaine de longueur 8
    $code = random(10);

    echo 'LV-'.$code;
}