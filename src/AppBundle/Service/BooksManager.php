<?php

// src/AppBundle/Service/BooksManager.php
namespace AppBundle\Service;

use UserBundle\Entity\User;
use AppBundle\Entity\Book;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Loan;
use Doctrine\ORM\EntityManagerInterface;
use \Datetime;

/**
 * Class BooksManager.
 */
class BooksManager
{
    protected $entityManager = null;

    /**
     * BooksManager constructor.
     *
     * @param Doctrine\ORM\EntityManagerInterface $em Entity Manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * Get repository
     *
     * @param string $entityClass
     *
     * @return Doctrine\ORM\EntityManagerInterface
     */
    public function getRepository($entityClass)
    {
        return $this->entityManager->getRepository($entityClass);
    }

    /**
     * Make reservation
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return AppBundle\Entity\Reservation
     *
     */
    public function makeReservation($user, $book)
    {
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

    /**
     * Whether user has reservations
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return bool
     */
    public function hasReservation($user, $book)
    {
        return $this->findReservation($user, $book) !== null;
    }

    /**
     * Whether can make a reservation
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return bool
     */
    public function canMakeReservation($user, $book)
    {
        return !$this->hasReservation($user, $book) && $book->getCurrentlyAvailable() > 0;
    }

    /**
     * Cancel reservation
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return bool
     */
    public function cancelReservation($user, $book)
    {
        $reservation = $this->findReservation($user, $book);
        if ($reservation) {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    /**
     * Whether can loan
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return bool
     */
    public function canLoan($user, $book)
    {
        return $this->hasReservation($user, $book);
    }

    /**
     * whether has a loan
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return bool
     */
    public function hasLoan($user, $book)
    {
        return $this->findLoan($user, $book) !== null;
    }

    /**
     * Make a loan
     *
     * @param AppBundle\Entity\Reservation $reservation
     *
     * @return AppBundle\Entity\Loan or null
     */
    public function makeALoan($reservation)
    {
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

    /**
     * Return a book
     *
     * @param AppBundle\Entity\Loan $loan Loan
     *
     * @return bool
     */
    public function returnBook($loan)
    {
        $this->entityManager->remove($loan);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Create book
     *
     * @param AppBundle\Entity\Book $book Book
     */
    public function createBook($book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    /**
     * Update book
     *
     * @param AppBundle\Entity\Book $book Book
     */
    public function updateBook($book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    /**
     * Delete book
     *
     * @param AppBundle\Entity\Book $book Book
     */
    public function deleteBook($book)
    {
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    /**
     * Find reservation
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return AppBundle\Entity\Reservation Reservation
     */
    private function findReservation($user, $book)
    {
        $reservationsRepo = $this->entityManager->getRepository(Reservation::class);
        $reservations = $reservationsRepo->findBy(array(
            'user' => $user,
            'book' => $book,
        ));

        return count($reservations) === 0? null : $reservations[0];
    }

    /**
     * Find loan
     *
     * @param UserBundle\Entity\User $user User
     *
     * @param AppBundle\Entity\Book  $book Book
     *
     * @return AppBundle\Entity\Loan Loan
     */
    private function findLoan($user, $book)
    {
        $loansRepo = $this->entityManager->getRepository(Loan::class);
        $loans = $loansRepo->findBy(array(
            'user' => $user,
            'book' => $book,
        ));

        return count($loans) === 0? null : $loans[0];
    }
}
