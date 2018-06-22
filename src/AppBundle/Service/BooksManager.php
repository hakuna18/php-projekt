<?php

// src/AppBundle/Service/BooksManager.php
namespace AppBundle\Service;

use AppBundle\Entity\Book;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Loan;
use Doctrine\ORM\EntityManagerInterface;
use \Datetime;

class BooksManager
{
    private static $RESERVATION_DAYS_LIMIT = 3;
    private static $LOAN_DAYS_LIMIT = 30;

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

    public function makeReservation($user, $book) {
        if ($this->canMakeReservation($user, $book)) {          
            $reservation = new Reservation();
            $reservation->setUser($user);
            $reservation->setBook($book);

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $reservation;
        } 
        return null;
    }

    public function hasReservation($user, $book) {
        return $this->findReservation($user, $book) != null;
    }

    public function reservationExpired($reservation) {
        return time() - $reservation->getCreationDate()->getTimestamp() > 3600 * 24 * BooksManager::$RESERVATION_DAYS_LIMIT;
    }

    public function canMakeReservation($user, $book) {
        return !$this->hasReservation($user, $book) && $book->getCurrentlyAvailable() > 0;
    }

    public function cancelReservation($user, $book) {
        $reservation = $this->findReservation($user, $book);
        if ($reservation) {
            $this->entityManager->remove($reservation);      
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function canLoan($user, $book) {
        return $this->hasReservation($user, $book);
    }

    public function loanExpired($loan) {
        return time() - $loan->getLoanDate()->getTimestamp() > 3600 * 24 * BooksManager::$LOAN_DAYS_LIMIT;
    }

    public function makeALoan($reservation) {
        $user = $reservation->getUser();
        $book = $reservation->getBook();
        if ($this->canLoan($user, $book)) {
            $loan = new Loan();
            $loan->setUser($reservation->getUser());
            $loan->setBook($reservation->getBook());
            $this->entityManager->persist($loan);
    
            // usun rezerwacje
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
            
            return $loan;
        }
        return null;
    }

    public function returnBook($loan) {
        $this->entityManager->remove($loan);      
        $this->entityManager->flush();
        return true;
    }

    private function findReservation($user, $book) {
        $reservationsRepo = $this->entityManager->getRepository(Reservation::class);
        $reservations = $reservationsRepo->findBy(array(
            'user' => $user,
            'book' => $book
        ));
        return count($reservations) == 0? null : $reservations[0];
    }
}