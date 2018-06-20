<?php

// src/AppBundle/Service/BooksManager.php
namespace AppBundle\Service;

use AppBundle\Entity\Book;
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

    public function getRepository($entityClass) {
        return $this->entityManager->getRepository($entityClass);
    }

    public function makeReservation($bookId, $userId) {
        $book = $this->getRepository(Book::class)->findOneById($bookId);
        if ($this->canMakeReservation($book, $userId)) {
            $book->setCurrentlyAvailable($book->getCurrentlyAvailable() - 1);
            
            $reservation = new Reservation();
            $reservation->setUserID($userId);
            $reservation->setBookID($bookId);
            $reservation->setReservationDate(new DateTime('now'));

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $reservation;
        } 
        return null;
    }

    public function hasReservation($bookId, $userId) {
        $reservationsRepo = $this->entityManager->getRepository(Reservation::class);
        $reservation = $reservationsRepo->findBy(array(
            'bookID' => $bookId,
            'userID' => $userId
        ));
        return $reservation != null;
    }

    public function reservationExpired($reservation) {
        return time() - $reservation->getReservationDate()->getTimestamp() > 3600 * 24 * 3; // 3 dni
    }

    public function canMakeReservation($book, $userId) {
        return !$this->hasReservation($book->getId(), $userId) && $book->getCurrentlyAvailable() > 0;
    }

    public function cancelReservation($bookId, $userId) {
        $reservationsRepo = $this->entityManager->getRepository(Reservation::class);
        $reservation = $reservationsRepo->findBy(array(
            'bookID' => $bookId,
            'userID' => $userId
        )); 
        if ($reservation) {
            // zaktualizuj tabele ksiazki
            $book = $this->getRepository(Book::class)->findOneById($bookId);
            $book->setCurrentlyAvailable($book->getCurrentlyAvailable() + 1);
            // usun rezerwacje
            $this->entityManager->remove($reservation[0]);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function canCheckout($bookId, $userId) {
        return $this->hasReservation($bookId, $userId);
    }

    public function checkoutBook($reservation) {
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
        return true;
    }

    public function returnBook($book, $user) {

    }
}