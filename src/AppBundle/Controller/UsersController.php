<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Repository\UsersRepository;
use UserBundle\Entity\User;
use AppBundle\Service\UsersManager;

/**
 * Users controller.
 *
 * @Route("/users")
 */
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
     *     "/",
     *     defaults={"page": 1},
     *     name="users",
     * )
     * @Route(
     *     "/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="users_paginated",
     *)
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request, $page)
    {
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
                'search_query' => $searchQuery,
            ]
        );
    }

    /**
     * @Route("/view/{id}", name="user_view")
     */
    public function viewAction(Request $request, User $user)
    {
        return $this->render(
            'admin/user.html.twig',
            ['user' => $user]
        );
    }

    /**
     * @Route("/edit/{id}", name="user_edit")
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'form.submit'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersManager->updateUser($user);
            $this->addFlash(
                'success',
                'operation_successful'
            );
        }

        return $this->render(
            'admin/edit_user.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function deleteAction(Request $request, User $user)
    {
        // Prevent deletion of another user by non-admin
        $isCurrentUserAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        if (!$isCurrentUserAdmin && $user != $this->getUser()) {
            throw $this->createAccessDeniedException("You cannot access this page!");
        }

        $this->usersManager->deleteUser($user); 
        $this->addFlash(
            'notice',
            'user_deleted.confirmation'
        );

        return $this->redirectToRoute('users');
    }

    /**
     * @Route("/panel", name="user_panel")
     */
    public function panelAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->render(
                'users/panel.html.twig',
                [
                    'user' => $this->getUser(),
                ]
            );
        }

        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/change_mail", name="user_change_mail")
     */
    public function changeMailAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user)
            ->add('email')
            ->add('save', SubmitType::class, array('label' => 'form.submit'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersManager->updateUser($user);
            $this->addFlash(
                'success',
                'mail_updated.confirmation'
            );
        }

        return $this->render(
            'users/change_mail.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
