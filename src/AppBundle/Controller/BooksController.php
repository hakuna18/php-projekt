<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;

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

    /**
     * BooksController constructor.
     *
     * @param \AppBundle\Repository\BooksRepository $booksRepository Books repository
     * @param \AppBundle\Service\BooksManager $booksManager Books manager service
     */
    public function __construct(BooksRepository $booksRepository, BooksManager $booksManager)
    {
        $this->booksRepository = $booksRepository;
        $this->booksManager = $booksManager;
    }

    /**
     * Index action.
     *
     * @Route(
     *     "/",
     *     name="books_catalogue",
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request) {
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
     * @Route("/reservation/{id}", name="make_reservation")
     */
    public function makeReservationAction($id) {
        $user = $this->getUser();
        if ($user) {
            $book = $this->booksRepository->findOneById($id);
            $reservation = $this->booksManager->makeReservation($book, $user);
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
     * @Route("/reservation/cancel/{id}", name="cancel_reservation")
     */
    public function cancelReservationAction($id) {
        $book = $this->booksRepository->findOneById($id);
        $user = $this->getUser();
        if ($this->booksManager->cancelReservation($book, $user)) {
            $this->addFlash(
                'notice',
                'Rezerwacja anulowana!'
            );
        }
        return $this->redirectToRoute('books_catalogue');
    }

    /**
     * @Route("/admin/checkout/{id}", name="checkout")
     */
    public function checkoutAction($id) {
        // TODO
        return $this->redirectToRoute('books_catalogue');
    }
}
