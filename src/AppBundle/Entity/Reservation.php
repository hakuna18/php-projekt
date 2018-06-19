<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
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
     * @var int
     *
     * @ORM\Column(name="userID", type="integer")
     */
    private $userID;

    /**
     * @var int
     *
     * @ORM\Column(name="bookID", type="integer")
     */
    private $bookID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservationDate", type="date")
     */
    private $reservationDate;


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
     * Set userID
     *
     * @param integer $userID
     *
     * @return Reservation
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;

        return $this;
    }

    /**
     * Get userID
     *
     * @return int
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Set bookID
     *
     * @param integer $bookID
     *
     * @return Reservation
     */
    public function setBookID($bookID)
    {
        $this->bookID = $bookID;

        return $this;
    }

    /**
     * Get bookID
     *
     * @return int
     */
    public function getBookID()
    {
        return $this->bookID;
    }

    /**
     * Set reservationDate
     *
     * @param \DateTime $reservationDate
     *
     * @return Reservation
     */
    public function setReservationDate($reservationDate)
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    /**
     * Get reservationDate
     *
     * @return \DateTime
     */
    public function getReservationDate()
    {
        return $this->reservationDate;
    }
}

