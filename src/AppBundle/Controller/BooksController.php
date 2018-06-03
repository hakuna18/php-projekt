<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\BooksRepository;

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
    public function indexAction(Request $request)
    {
        return $this->render(
            'books/index.html.twig',
            ['books' => $this->booksRepository->findAll()]
        );
    }

    /**
     * @Route("/?search={query}", name="books_search")
     */
    public function search($query) {
        return $this->render(
            'books/index.html.twig',
            ['books' => $this->booksRepository->findByStr($query)]
        );
    }
}
