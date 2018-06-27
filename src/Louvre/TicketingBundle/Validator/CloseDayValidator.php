<?php

namespace Louvre\TicketingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CloseDayValidator extends ConstraintValidator
{

  public function validate($date, Constraint $constraint)
  {

  	$util = new \Checkdomain\Holiday\Util();
    
  	//Date en string
  	$date = date_format($date, 'd-m-Y');

  	//Jour férié
  	$holiday = $util->getHoliday('FR', $date);

  	//Jour de la semaine
  	$day = date("l", strtotime($date));

  	// Vérification si jour férié -> erreur
  	if($holiday != null){

  		$this->context->addViolation($constraint->closeHoliday);
  	}

  	// Vérification si la date est un mardi ou un dimanche -> erreur
   	elseif($day == "Tuesday"){

  		$this->context->addViolation($constraint->dayTuesday);
  	}
    elseif($day == "Sunday"){
      $this->context->addViolation($constraint->daySunday);
    }
  
  }
}