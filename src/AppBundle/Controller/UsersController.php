<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\UsersRepository;

class UsersController extends Controller
{
    // /**
    //  * Users repository.
    //  *
    //  * @var \AppBundle\Repository\UsersRepository|null Users repository
    //  */
    // protected $usersRepository = null;

    // /**
    //  * UsersController constructor.
    //  *
    //  * @param \AppBundle\Repository\UsersRepository $usersRepository Users repository
    //  */
    // public function __construct(UsersRepository $usersRepository)
    // {
    //     $this->usersRepository = $usersRepository;
    // }

    /**
     * @Route("/", name="users")
     */
    public function indexAction(Request $request)
    {
        return $this->render(
            'users/index.html.twig',
            ['users' => $this->usersRepository->findAll()]
        );
    }

    /**
     * @Route("/?search={query}", name="users_search")
     */
    public function search($query) {
        return $this->render(
            'users/index.html.twig',
            ['users' => $this->usersRepository->findByStr($query)]
        );
    }
}
