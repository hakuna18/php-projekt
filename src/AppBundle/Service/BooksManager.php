<?php

// src/AppBundle/Service/BooksManager.php
namespace AppBundle\Service;

use AppBundle\Entity\Checkout;
use AppBundle\Entity\Reservation;
use Doctrine\ORM\EntityManager;
use \Datetime;

class BooksManager
{
    protected $entityManager = null;

    /**
     * BooksManager constructor.
     *
     * @param Doctrine\ORM\EntityManager $em Entity Manager
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function makeReservation($book, $user) {
        $availableCount = $book->getCurrentlyAvailable();
        if ($availableCount > 0) {
            $book->setCurrentlyAvailable($availableCount - 1);
            
            $reservation = new Reservation();
            $reservation->setUserID($user->getId());
            $reservation->setBookID($book->getId());
            $reservation->setReservationDate(new DateTime('now'));

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $reservation;
        } 
        return null;
    }

    public function cancelReservation($book, $user) {

    }

    public function lendBook($book, $user) {
        
    }

    public function returnBook($book, $user) {

    }
}