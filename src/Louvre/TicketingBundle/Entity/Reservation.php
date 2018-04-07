<?php

namespace Louvre\TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="Louvre\TicketingBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @var \DateTime
     *
     * @ORM\Column(name="day", type="date")
     * @Assert\GreaterThanOrEqual("today UTC", message="Vous ne pouvez pas réservez pour les jours passés")
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="reservationCode", type="string", length=255, unique=true)
     */
    private $reservationCode;

    /**
     * @ORM\OneToMany(targetEntity="Louvre\TicketingBundle\Entity\Billet", mappedBy="reservation")
     * @Assert\Valid
     */
    private $billet;


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
     * Set day
     *
     * @param \DateTime $day
     *
     * @return Reservation
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set reservationCode
     *
     * @param string $reservationCode
     *
     * @return Reservation
     */
    public function setReservationCode($reservationCode)
    {
        $this->reservationCode = $reservationCode;

        return $this;
    }

    /**
     * Get reservationCode
     *
     * @return string
     */
    public function getReservationCode()
    {
        return $this->reservationCode;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add billet
     *
     * @param \Louvre\TicketingBundle\Entity\Billet $billet
     *
     * @return Reservation
     */
    public function addBillet(\Louvre\TicketingBundle\Entity\Billet $billet)
    {
        $this->billet[] = $billet;

        return $this;
    }

    /**
     * Remove billet
     *
     * @param \Louvre\TicketingBundle\Entity\Billet $billet
     */
    public function removeBillet(\Louvre\TicketingBundle\Entity\Billet $billet)
    {
        $this->billet->removeElement($billet);
    }

    /**
     * Get billet
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillet()
    {
        return $this->billet;
    }
}
