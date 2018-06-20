<?php

// src/AppBundle/Service/BooksManager.php
namespace AppBundle\Service;

use AppBundle\Entity\Checkout;
use AppBundle\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use \Datetime;

class BooksManager
{
    protected $entityManager = null;

    /**
     * BooksManager constructor.
     *
     * @param Doctrine\ORM\EntityManager $em Entity Manager
     */
    public function __construct(EntityManagerInterface $em)
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

    public function hasReservation($book, $user) {
        $reservationsRepo = $this->entityManager->getRepository(Reservation::class);
        $reservation = $reservationsRepo->findBy(array(
            'bookID' => $book->getId(),
            'userID' => $user->getId()
        ));
        return $reservation != null;
    }

    public function canMakeReservation($book, $user) {
        return !$this->hasReservation($book, $user) && $book->getCurrentlyAvailable() > 0;
    }

    public function cancelReservation($book, $user) {
        $reservationsRepo = $this->entityManager->getRepository(Reservation::class);
        $reservation = $reservationsRepo->findBy(array(
            'bookID' => $book->getId(),
            'userID' => $user->getId()
        )); 
        if ($reservation) {
            $book->setCurrentlyAvailable($book->getCurrentlyAvailable() + 1);
            $this->entityManager->remove($reservation[0]);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function lendBook($book, $user) {
        
    }

    public function returnBook($book, $user) {

    }
}