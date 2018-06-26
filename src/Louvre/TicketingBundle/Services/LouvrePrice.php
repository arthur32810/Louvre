<?php

namespace Louvre\TicketingBundle\Services;

use \DateTime;

class LouvrePrice
{

	private $normalPrice;
	private $childrenPrice;
	private $seniorPrice;
	private $reducePrice;

	private $childrenAge;
	private $normalAge;
	private $seniorAge;

	public function __construct($normalPrice, $childrenPrice, $seniorPrice, $reducePrice, $childrenAge, $normalAge, $seniorAge){

		$this->normalPrice 		= $normalPrice;
		$this->childrenPrice	= $childrenPrice;
		$this->seniorPrice 		= $seniorPrice;
		$this->reducePrice 		= $reducePrice;
		$this->childrenAge		= $childrenAge;
		$this->normalAge		= $normalAge;
		$this->seniorAge		= $seniorAge;
	}

	public function price($reduction, $birthday)
	{	
	

		if($reduction)
		{
			return $this->reducePrice;
		}

		else 
		{
			// Création de la date du jour
			$day = new DateTime(date('m/d/Y'));

			if($birthday < $day)
			{
				// Calcul de l'âge
				$age = $birthday->diff($day);
				$age = intval($age->format('%y'));

				if($age < $this->childrenAge)
				{
					return 0;
				}
				elseif($age >= $this->childrenAge && $age < $this->normalAge)
				{
					return $this->childrenPrice;
				}
				elseif($age >= $this->normalAge && $age < $this->seniorAge)
				{
					return $this->normalPrice;
				}
				elseif($age >= $this->seniorAge)
				{
					return $this->seniorPrice;
				}
			}
			else
			{
				throw new \LogicException("Il y a eu une erreur dans le calcul de l'âge, veuillez soumettre de nouveau le formulaire");
			}
			
		}
		
		
	}
}