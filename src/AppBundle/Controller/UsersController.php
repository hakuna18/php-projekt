<?php
/**
 * UsersController.
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use UserBundle\Entity\User;
use AppBundle\Service\UsersManager;

/**
 * Users controller.
 *
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * Users manager
     */
    private $usersManager = null;

    /**
     * UsersController constructor.
     *
     * @param \AppBundle\Service\UsersManager $usersManager Users manager service
     */
    public function __construct(UsersManager $usersManager)
    {
        $this->usersManager = $usersManager;
    }

    /**
     * Index action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param integer                                  $page    Current page number
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
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request, $page)
    {
        $searchQuery = $request->request->get('form')['search'];
        $matchedUsers = $this->usersManager->query($searchQuery, $page);
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
     * View single user action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param UserBundle\Entity\User                   $user    User
     *
     * @Route("/view/{id}", name="user_view")
     *
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function viewAction(Request $request, User $user)
    {
        return $this->render(
            'admin/user.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Edit user action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param UserBundle\Entity\User                   $user    User
     *
     * @Route("/edit/{id}", name="user_edit")
     *
     * @Method({"GET, POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
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
     * Delete user action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @param UserBundle\Entity\User                   $user    User
     *
     * @Route("/delete/{id}", name="user_delete")
     *
     * @Method({"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function deleteAction(Request $request, User $user)
    {
        // 1) Only reader can delete self
        // 2) Admin can delete admins until there is one admin left
        if (($this->getUser()->hasRole('ROLE_READER') && $this->getUser() !== $user)
            || ($this->getUser()->hasRole('ROLE_ADMIN') && $user->hasRole('ROLE_ADMIN') && count($this->usersManager->findUsersByRole('ROLE_ADMIN')) === 1)
            ) {
                throw $this->createAccessDeniedException("You cannot access this page!");
        }

        // forward to the confirmation Controller
        $message = '';
        if ($this->getUser() === $user) {
            $message =  $this->get('translator')->trans('Are you sure you want to delete your account?');
        } else {
            $message = $this->get('translator')->trans(
                'Are you sure you want to delete user: %username% (%name% %surname%)?',
                ['%username%' => $user->getUsername(), '%name%' => $user->getName(), '%surname%' => $user->getSurname()]
            );
        }
        // if deleting self redirect to /login, otherwise to /users
        $targetPath = $this->getUser() === $user? 'fos_user_security_login' : 'users';
        $args = [
            'message' => $message,
            'targetUrl' => $this->generateUrl($targetPath),
            'cancelUrl' => $request->headers->get('referer'),
            'confirmCallback' => function ($user) {
                $this->usersManager->deleteUser($user);
                $this->addFlash('notice', 'user_deleted.confirmation');
            },
            'confirmCallbackArgs' => $user,
        ];

        return $this->forward('AppBundle:Confirm:confirm', ['args' => $args]);
    }

    /**
     * View panel action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/panel", name="user_panel")
     *
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
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
     * Change mail action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/change_mail", name="user_change_mail")
     *
     * @Method({"GET, POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
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
