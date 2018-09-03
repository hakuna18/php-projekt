<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use AppBundle\Entity\Book;
use AppBundle\Entity\Loan;
use AppBundle\Entity\Reservation;
use AppBundle\Form\BookType;
use AppBundle\Repository\BooksRepository;
use AppBundle\Service\BooksManager;


class BooksController extends Controller
{
    /**
     * Books repository.
     *
     * @var \AppBundle\Repository\BooksRepository|null Books repository
     */
    protected $booksRepository = null;

    protected $loansRepository = null;

    protected $reservationsRepository = null;

    protected $booksManager = null;


    /**
     * BooksController constructor.
     *
     * @param \AppBundle\Repository\BooksRepository $booksRepository Books repository
     * @param \AppBundle\Service\BooksManager $booksManager Books manager service
     */
    public function __construct(BooksManager $booksManager)
    {
        $this->booksRepository = $booksManager->getRepository(Book::class);
        $this->loansRepository = $booksManager->getRepository(Loan::class);
        $this->reservationsRepository = $booksManager->getRepository(Reservation::class);
        $this->booksManager = $booksManager;
    }

    /**
     * Index action.
     *
     * @param integer $page Current page number
     * 
     * @Route(
     *     "/books",
     *     defaults={"page": 1},
     *     name="books_catalogue",
     * )
     * @Route(
     *     "/books/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="books_catalogue_paginated",
     *)
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request, $page) {
        $searchQuery = $request->request->get('form')['search'];

        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render(
            'books/index.html.twig',
            [
                'books' => $this->booksRepository->findByPattern($searchQuery, $page),
                'form' => $form->createView(),
                'booksManager' => $this->booksManager,
                'search_query' => $searchQuery
            ]
        );
    }

   /**
     * View action.
     *
     * @Route(
     *     "/books/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="book_view",
     * )
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */

    public function viewAction($id)
    {
        $book = $this->booksRepository->findOneById($id);

        return $this->render(
            'books/view.html.twig',
            ['book' => $book]
        );
    }

     /**
     * @Route("/reservation/make/{id}", name="make_reservation")
     */
    public function makeReservationAction(Request $request, Book $book) {
        $user = $this->getUser();
        $reservation = $this->booksManager->makeReservation($user, $book);
        if ($reservation) {
             $this->addFlash(
                'notice',
                'book.reservation.confirmation'
            );
        }
        return $this->redirectToRoute('books_catalogue');
    }

     /**
     * @Route("/reservation/cancel/{id}", name="cancel_reservation")
     */
    public function cancelReservationAction(Request $request, Book $book) {
        $userId = $request->query->get('userId');
        if ($userId) {
            $user = $this->get('fos_user.user_manager')->findUserBy(array('id' => $userId));
        } else {
            $user = $this->getUser();
        }
        if ($this->booksManager->cancelReservation($user, $book)) {
            $this->addFlash(
                'notice',
                'book.reservation.cancel_confirmation'
            );
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/loan/make/{id}", name="make_loan")
     */
    public function loanAction(Request $request, Reservation $reservation) {
        if ($this->booksManager->makeALoan($reservation)) {
            $this->addFlash(
                'notice',
                'book.loan.confirmation'
            );
        }  
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/loan/return/{id}", name="book_return")
     */
    public function returnBookAction(Request $request, Loan $loan) {
        if ($loan && $this->booksManager->returnBook($loan)) {
            $this->addFlash(
                'notice',
                'book.return.confirmation'
            );
        }  
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Add action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/books/add",
     *     name="books_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->booksRepository->createBook($book);
            $this->addFlash('success', 'message.created_successfully');
        }

        return $this->render(
            'books/add.html.twig',
            [
                'book' => $book,
                'form' => $form->createView(),
            ]
        );
    }

    /**
    * @Route(
    *     "/books/edit/{id}",
    *     name="book_edit",
    * )
    * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Book $book)
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->booksManager->updateBook($book);
            $this->addFlash('success', 'operation_successful');
        }

        return $this->render(
            'books/edit.html.twig',
            [
                'book' => $book,
                'form' => $form->createView(),
            ]
        );
    }

    /**
    * @Route(
    *     "/books/delete/{id}",
    *     name="book_delete",
    * )
    * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Book $book)
    {
        $this->booksManager->deleteBook($book);
        $this->addFlash('success', 'book_deleted.confirmation');

        return $this->redirectToRoute('books_catalogue');
    }
}
