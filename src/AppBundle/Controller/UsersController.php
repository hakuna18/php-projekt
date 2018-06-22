<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\UsersRepository;
use FOS\UserBundle\Doctrine\UserManager;

use UserBundle\Entity\User;

class UsersController extends Controller
{
    /**
     * @Route("/admin/users", name="users")
     */
    public function indexAction(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        return $this->render(
            'users/index.html.twig',
            ['users' => $userManager->findUsers()]
        );
    }

    /**
     * @Route("/user/panel", name="user_panel")
     */
    public function panelAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->render('users/panel.html.twig', ["user" => $this->getUser()]);
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteAction(Request $request, User $user)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->deleteUser($user);

        $this->addFlash(
            'notice',
            'user_deleted.confirmation'
        );
        $this->redirectToRoute('books_catalogue');
    }

    /**
     * @Route("/?search={query}", name="users_search")
     */
    public function search($query) {
        return $this->render(
            'users/index.html.twig',
            ['users' => $userManager->findUsers()->findByStr($query)]
        );
    }
}
