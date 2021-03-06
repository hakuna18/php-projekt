<?php

/**
 * BooksManager.
 */
namespace AppBundle\Service;

use AppBundle\Entity\Book;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Loan;
use UserBundle\Entity\User;
use AppBundle\Repository\BookRepository;
use AppBundle\Repository\ReservationRepository;
use AppBundle\Repository\LoanRepository;
use \Datetime;

/**
 * Class BooksManager.
 */
class BooksManager
{
    /**
     * Book repository
     */
    private $bookRepository = null;

    /**
     * Reservation repository
     */
    private $reservationRepository = null;

    /**
     * Loan repository
     */
    private $loanRepository = null;

    /**
     * BooksManager constructor.
     *
     * @param AppBundle\Repository\BookRepository        $bookRepository        Book repository
     * @param AppBundle\Repository\ReservationRepository $reservationRepository Reservation repository
     * @param AppBundle\Repository\LoanRepository        $loanRepository        Loan repository
     */
    public function __construct(BookRepository $bookRepository, ReservationRepository $reservationRepository, LoanRepository $loanRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->reservationRepository = $reservationRepository;
        $this->loanRepository = $loanRepository;
    }

    /**
     * Get Book Entity repository
     *
     * @return AppBundle\Repository\BookRepository
     */
    public function getBookRepository()
    {
        return $this->bookRepository;
    }

    /**
     * Get Reservation Entity repository
     *
     * @return AppBundle\Repository\ReservationRepository
     */
    public function getReservationRepository()
    {
        return $this->reservationRepository;
    }

    /**
     * Get Loan Entity repository
     *
     * @return AppBundle\Repository\LoanRepository
     */
    public function getLoanRepository()
    {
        return $this->loanRepository;
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

            $this->reservationRepository->save($reservation);

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
            $this->reservationRepository->delete($reservation);

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
            $this->loanRepository->save($loan);
            // usun rezerwacje
            $this->reservationRepository->delete($reservation);

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
        $this->loanRepository->delete($loan);

        return true;
    }

    /**
     * Create book
     *
     * @param AppBundle\Entity\Book $book Book
     */
    public function createBook($book)
    {
        $this->bookRepository->save($book);
    }

    /**
     * Update book
     *
     * @param AppBundle\Entity\Book $book Book
     */
    public function updateBook($book)
    {
        $this->bookRepository->save($book);
    }

    /**
     * Delete book
     *
     * @param AppBundle\Entity\Book $book Book
     */
    public function deleteBook($book)
    {
        $this->bookRepository->delete($book);
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
        $reservations = $this->reservationRepository->findBy(array(
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
        $loans = $this->loanRepository->findBy(array(
            'user' => $user,
            'book' => $book,
        ));

        return count($loans) === 0? null : $loans[0];
    }
}
