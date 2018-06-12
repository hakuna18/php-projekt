<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\BooksRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BooksController extends Controller
{
    /**
     * Books repository.
     *
     * @var \AppBundle\Repository\BooksRepository|null Books repository
     */
    protected $booksRepository = null;

    /**
     * BooksController constructor.
     *
     * @param \AppBundle\Repository\BooksRepository $booksRepository Books repository
     */
    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    /**
     * @Route("/", name="books_catalogue")
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
                'form' => $form->createView()
            ]
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
}
