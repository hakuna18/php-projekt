<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Checkout
 *
 * @ORM\Table(name="checkout")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CheckoutRepository")
 */
class Checkout
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
     * @ORM\Column(name="checkoutDate", type="date")
     */
    private $checkoutDate;


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
     * @return Checkout
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
     * @return Checkout
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
     * Set checkoutDate
     *
     * @param \DateTime $checkoutDate
     *
     * @return Checkout
     */
    public function setCheckoutDate($checkoutDate)
    {
        $this->checkoutDate = $checkoutDate;

        return $this;
    }

    /**
     * Get checkoutDate
     *
     * @return \DateTime
     */
    public function getCheckoutDate()
    {
        return $this->checkoutDate;
    }
}

