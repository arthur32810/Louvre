<?php

namespace Louvre\TicketingBundle\Services;

use \DateTime;

class LouvrePrice
{

	private $normalPrice;
	private $childrenPrice;
	private $seniorPrice;
	private $reducePrice;

	public function __construct($normalPrice, $childrenPrice, $seniorPrice, $reducePrice){

		$this->normalPrice 		= $normalPrice;
		$this->childrenPrice	= $childrenPrice;
		$this->seniorPrice 		= $seniorPrice;
		$this->reducePrice 		= $reducePrice;
	}

<<<<<<< HEAD
	public function price($billets)
	{	
		
		if(count($billets) >0)
		{

			foreach ($billets as $billet) {
				
				$reduction = $billet->getReduction();

				if($reduction)
				{
					$billet->setPrice($this->reducePrice);
				}

				else 
				{
					// Création de la date d'anniversaire
					$birthday = $billet->getBirthday();


					// Création de la date du jour
					$day = new DateTime(date('m/d/Y'));

					if($birthday < $day)
					{
						// Calcul de l'âge
						$age = $birthday->diff($day);
						$age = intval($age->format('%y'));

						if($age < 4)
						{
							return 0;
						}
						elseif($age >= 4 && $age < 12)
						{
							$billet->setPrice($this->childrenPrice);
						}
						elseif($age >= 12 && $age < 60)
						{
							$billet->setPrice($this->normalPrice);
						}
						elseif($age >= 60)
						{
							$billet->setPrice($this->seniorPrice);
						}
						else
						{
							echo "Il y a eu une erreur dans le calcul de l'âge";
						}	

					}
					
				}
				
			}
		}
		
=======
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

				if($age < 4)
				{
					return 0;
				}
				elseif($age >= 4 && $age < 12)
				{
					return $this->childrenPrice;
				}
				elseif($age >= 12 && $age < 60)
				{
					return $this->normalPrice;
				}
				elseif($age >= 60)
				{
					return $this->seniorPrice;
				}
			}
			else
			{
				throw new \LogicException("Il y a eu une erreur dans le calcul de l'âge, veuillez soumettre de nouveau le formulaire");
			}
			
		}
		
		
>>>>>>> tests
	}
}