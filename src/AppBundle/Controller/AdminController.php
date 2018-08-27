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
use AppBundle\Service\UsersManager;


class AdminController extends Controller
{
    protected $usersManager = null;

    protected $booksManager = null; 

    public function __construct(UsersManager $usersManager, BooksManager $booksManager)
    {
        $this->usersManager = $usersManager;
        $this->booksManager = $booksManager;
    }

    /**
     * @Route("/admin_panel", name="admin_panel")
     */
    public function adminPanelAction(Request $request) {
        $reservations =  $this->booksManager->getRepository(Reservation::class)->findAll();
        $loans = $this->booksManager->getRepository(Loan::class)->findAll();
        $users = $this->usersManager->getAllUsers();

        return $this->render(
            'admin/panel.html.twig',
            [
                'reservations' => $reservations,
                'loans' => $loans,
                'users' => $users
            ]
        );
    }
}