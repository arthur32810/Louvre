<?php

namespace Louvre\TicketingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class CloseDay extends Constraint
{

  	public $closeDay = "Musée fermé le mardi et dimanche";
  	public $closeHoliday= "Musée fermé les jours fériés";

}