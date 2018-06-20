<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use AppBundle\Entity\Book;
use AppBundle\Entity\Checkout;
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

    protected $booksManager = null;

    protected $checkoutsRepository = null;

    protected $reservationsRepository = null;


    /**
     * BooksController constructor.
     *
     * @param \AppBundle\Repository\BooksRepository $booksRepository Books repository
     * @param \AppBundle\Service\BooksManager $booksManager Books manager service
     */
    public function __construct(BooksManager $booksManager)
    {
        $this->booksRepository = $booksManager->getRepository(Book::class);
        $this->checkoutRepository = $booksManager->getRepository(Checkout::class);
        $this->reservationsRepository = $booksManager->getRepository(Reservation::class);
        $this->booksManager = $booksManager;
    }

    /**
     * Index action.
     *
     * @param integer $page Current page number
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
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request, $page) {
        $query = $request->request->get('form')['search'];

        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render(
            'books/index.html.twig',
            [
                'books' => $this->booksRepository->findByPattern($query),
                'form' => $form->createView(),
                'booksManager' => $this->booksManager
            ]
        );
    }

   /**
     * View action.
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

    public function viewAction($id)
    {
        $book = $this->booksRepository->findOneById($id);

        return $this->render(
            'books/view.html.twig',
            ['book' => $book]
        );
    }
    
     /**
     * @Route("/admin", name="admin_panel")
     */
    public function adminAction(Request $request) {
        $books = $this->booksRepository->findAll();

        return $this->render(
            'books/admin.html.twig',
            [
                'books' => $books
            ]
        );
    }

     /**
     * @Route("/reservation/make/{bookId}", name="make_reservation")
     */
    public function makeReservationAction(Request $request, $bookId) {
        $userId = $request->query->get('userId');
        if (!$userId) {
            $userId = $this->getUser()->getId();
        }
        if ($bookId) {
            $reservation = $this->booksManager->makeReservation($bookId, $userId);
            if ($reservation) {
                $this->addFlash(
                    'notice',
                    'Rezerwacja dokonana!'
                );
            }
        }
        return $this->redirectToRoute('books_catalogue');
    }

     /**
     * @Route("/reservation/cancel/{bookId}", name="cancel_reservation")
     */
    public function cancelReservationAction(Request $request, $bookId) {
        $userId = $request->query->get('userId');
        if (!$userId) {
            $userId = $this->getUser()->getId();
        }
        if ($this->booksManager->cancelReservation($bookId, $userId)) {
            $this->addFlash(
                'notice',
                'Rezerwacja anulowana!'
            );
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/reservation/checkout/{id}", name="checkout")
     */
    public function checkoutAction(Request $request, Reservation $reservation) {
        if ($this->booksManager->checkoutBook($reservation)) {
            $this->addFlash(
                'notice',
                'Wypozyczono!'
            );
        }  
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin/reservations/{id}", name="reservations_checkouts")
     */
    public function reservationsAndCheckoutsAction($id) {
        $book = $this->booksRepository->findOneById($id);
        $reservations = $this->reservationsRepository->findByBookID($id);

        return $this->render(
            'books/reservations_checkouts.html.twig',
            [
                'book' => $book,
                'reservations' => $reservations,
                'booksManager' => $this->booksManager
            ]
        );
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
            $this->booksRepository->save($book);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('books_catalogue');
        }

        return $this->render(
            'books/add.html.twig',
            [
                'book' => $book,
                'form' => $form->createView(),
            ]
        );
    }
}
