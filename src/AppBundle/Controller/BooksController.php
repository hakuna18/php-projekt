<?php
/**
 * BooksController.
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\File;

use AppBundle\Entity\Book;
use AppBundle\Entity\Loan;
use AppBundle\Entity\Reservation;
use AppBundle\Form\BookType;
use AppBundle\Repository\BooksRepository;
use AppBundle\Service\BooksManager;

/**
 * Books controller.
 *
 * @Route("/books")
 */
class BooksController extends Controller
{
    /**
     * Books manager.
     *
     * @var \AppBundle\Service\BooksManager|null
     */
    protected $booksManager = null;

    /**
     * BooksController constructor.
     *
     * @param \AppBundle\Service\BooksManager $booksManager Books manager service
     */
    public function __construct(BooksManager $booksManager)
    {
        $this->booksManager = $booksManager;
    }

    /**
     * Index action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param integer                                  $page    Current page number
     *
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="books_catalogue",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="books_catalogue_paginated",
     *)
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $searchQuery = $request->request->get('form')['search'];
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render(
            'books/index.html.twig',
            [
                'books' => $this->booksManager->getBookRepository()->query($searchQuery, $page),
                'form' => $form->createView(),
                'booksManager' => $this->booksManager,
                'search_query' => $searchQuery,
            ]
        );
    }

    /**
     * View catalogue details.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param integer                                  $page    Current page number
     *
     * @Route(
     *     "/details",
     *     defaults={"page": 1},
     *     name="books_details",
     * )
     *
     * @Route(
     *     "/details/{page}",
     *     requirements={"id": "[1-9]\d*"},
     *     defaults={"page": 1},
     *     name="books_details_paginated",
     * )
     *
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function viewDetailsAction(Request $request, $page = 1)
    {
        $searchQuery = $request->request->get('form')['search'];
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render(
            'books/all.html.twig',
            [
                'books' => $this->booksManager->getBookRepository()->query($searchQuery, $page),
                'form' => $form->createView(),
                'search_query' => $searchQuery,
            ]
        );
    }


   /**
     * View single book action.
     *
     * @param AppBundle\Entity\Book $book Book
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="book_view",
     * )
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function viewSingleAction(Book $book)
    {
        return $this->render(
            'books/view.html.twig',
            ['book' => $book]
        );
    }

     /**
     * Make a reservation action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param AppBundle\Entity\Book                    $book    Book
     *
     * @Route("/reservation/make/{id}", name="make_reservation")
     *
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function makeReservationAction(Request $request, Book $book)
    {
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
     * Cancel reservation action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param AppBundle\Entity\Book                    $book    Book
     *
     * @Route("/reservation/cancel/{id}", name="cancel_reservation")
     *
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function cancelReservationAction(Request $request, Book $book)
    {
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
     * Change a reservation to a loan action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param AppBundle\Entity\Reservation             $reservation Reservation
     *
     * @Route("/loan/make/{id}", name="make_loan")
     *
     * @Method({"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function loanAction(Request $request, Reservation $reservation)
    {
        if ($this->booksManager->makeALoan($reservation)) {
            $this->addFlash(
                'notice',
                'book.loan.confirmation'
            );
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Return book action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param AppBundle\Entity\Loan                    $loan
     *
     * @Route("/loan/return/{id}", name="book_return")
     *
     * @Method({"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function returnBookAction(Request $request, Loan $loan)
    {
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
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/add",
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
            $this->booksManager->createBook($book);
            $this->addFlash('success', 'operation_successful');
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
    * Edit action.
    *
    * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
    *
    * @param AppBundle\Entity\Book                     $book    Book
    *
    * @Route(
    *     "/edit/{id}",
    *     name="book_edit",
    * )
    * @Method({"GET", "POST"})
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function editAction(Request $request, Book $book)
    {
        $backup = $book->getCover();
        $form = $this->createForm(BookType::class, $book, ['edit_mode' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->booksManager->updateBook($book);
            $this->addFlash('success', 'operation_successful');

            return $this->redirectToRoute('books_details');
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
    * Delete action.
    *
    * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
    *
    * @param AppBundle\Entity\Book                     $book    Book
    *
    * @Route(
    *     "/delete/{id}",
    *     name="book_delete",
    * )
    * @Method({"POST"})
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP Response
    */
    public function deleteAction(Request $request, Book $book)
    {
        // forward to the confirmation Controller
        $args = [
            'message' => $this->get('translator')->trans(
                'Are you sure you want to delete book: %title% (isbn: %isbn%)?',
                ['%title%' => $book->getTitle(), '%isbn%' => $book->getISBN()]
            ),
            'targetUrl' => $this->generateUrl('books_details'),
            'cancelUrl' => $request->headers->get('referer'),
            'confirmCallback' => function ($book) {
                $this->booksManager->deleteBook($book);
                $this->addFlash('success', 'book_deleted.confirmation');
            },
            'confirmCallbackArgs' => $book,
        ];

        return $this->forward('AppBundle:Confirm:confirm', ['args' => $args]);
    }
}
