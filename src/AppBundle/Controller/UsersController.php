<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use AppBundle\Repository\UsersRepository;
use UserBundle\Entity\User;
use AppBundle\Service\UsersManager;

class UsersController extends Controller
{
    private $usersManager = null;

    public function __construct(UsersManager $usersManager)
    {
        $this->usersManager = $usersManager;
    }

    /**
     * Index action.
     *
     * @param integer $page Current page number
     * 
     * @Route(
     *     "/users",
     *     defaults={"page": 1},
     *     name="users",
     * )
     * @Route(
     *     "/users/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="users_paginated",
     *)
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request, $page) {
        // Only admin is allowed to access users list.
        if(!$this->getUser() || !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException('You cannot access this page!');        
        }

        $searchQuery = $request->request->get('form')['search'];
        $matchedUsers = $this->usersManager->findByPattern($searchQuery, false, $page);
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render(
            'users/index.html.twig',
            [
                'users' => $matchedUsers,
                'form' => $form->createView(),
                'search_query' => $searchQuery
            ]
        );
    }

    /**
     * @Route("/users/view/{id}", name="user_view")
     */
    public function adminViewAction(Request $request, User $user) {
        return $this->render(
            'admin/user.html.twig',
            ['user' => $user]
        );
    }

    /**
     * @Route("/user/panel", name="user_panel")
     */
    public function panelAction(Request $request) {
        if ($this->getUser()) {
            return $this->render('users/panel.html.twig', ["user" => $this->getUser()]);
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteAction(Request $request, User $user) {
        $this->usersManager->deleteUser($user);
    
        $this->addFlash(
            'notice',
            'user_deleted.confirmation'
        );

        return $this->redirectToRoute('books_catalogue');
    }
}
