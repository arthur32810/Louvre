<?php

namespace Louvre\TicketingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class CloseDay extends Constraint
{

  	public $dayTuesday = "Musée fermé le mardi";
  	public $daySunday = "Pas de réservation possible le dimanche";
  	public $closeHoliday= "Musée fermé les jours fériés";

}