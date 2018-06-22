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
     * @Route("/admin/users", name="admin_users")
     */
    public function adminIndexAction(Request $request) {
        $searchQuery = $request->request->get('form')['search'];
        $matchedUsers = $this->usersManager->findUserByPattern($searchQuery);

        $searchQuery = $request->request->get('form')['search'];
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render(
            'users/index.html.twig',
            [
                'users' => $matchedUsers,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/admin/users/{id}", name="admin_user_view")
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
        $userManager = $this->container->get('user.users_manager');
        $userManager->deleteUser($user);
    
        $this->addFlash(
            'notice',
            'user_deleted.confirmation'
        );
        return $this->redirectToRoute('books_catalogue');
    }
}
