<?php

namespace Louvre\TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="Louvre\TicketingBundle\Repository\BilletRepository")
 */
class Billet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le nom doit comporter au moins {{ limit }} caractères")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le prénom doit comporter au moins {{ limit }} caractères")
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     * @Assert\LessThan("today UTC", message="Vous devez être né(e) avant aujourd'hui")
     * @Assert\LessThan("-4 years", message="L'entrée est gratuite pour les enfants de moins de 4 ans")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\Country()
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDay", type="date")
     */
    private $visitDay;

    /**
     * @var bool
     *
     * @ORM\Column(name="reduction", type="boolean")
     * @Assert\Type(type="bool", message="La valeur {{ value }} n'est pas un {{ type }} valide")
     */
    private $reduction;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="decimal", precision=10, scale=1)
     * @Assert\Regex("/1|0.5/", message="Ce champ n'accepte que 1 ou 0.5")
     */
    private $duration;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Louvre\TicketingBundle\Entity\Reservation", inversedBy="billet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;

    public function __construct()
    {
        $this->birthday = new \DateTime();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Billet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Billet
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Billet
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Billet
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set visitDay
     *
     * @param \DateTime $visitDay
     *
     * @return Billet
     */
    public function setVisitDay($visitDay)
    {
        $this->visitDay = $visitDay;

        return $this;
    }

    /**
     * Get visitDay
     *
     * @return \DateTime
     */
    public function getVisitDay()
    {
        return $this->visitDay;
    }

    /**
     * Set reduction
     *
     * @param boolean $reduction
     *
     * @return Billet
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return bool
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set duration
     *
     * @param string $duration
     *
     * @return Billet
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Billet
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set reservation
     *
     * @param \Louvre\TicketingBundle\Entity\Reservation $reservation
     *
     * @return Billet
     */
    public function setReservation(\Louvre\TicketingBundle\Entity\Reservation $reservation)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \Louvre\TicketingBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
