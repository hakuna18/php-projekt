<?php
/**
 * AdminController.
 */
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

/**
 * Class AdminController.
 *
 * @Route(
 *     "/admin"
 * )
 */
class AdminController extends Controller
{
    /**
     * Users manager
     */
    protected $usersManager = null;

    /**
     * Books manager
     */
    protected $booksManager = null;

/**
* AdminController constructor.
*
* @param \AppBundle\Service\UsersManager $usersManager Users manager service
* @param \AppBundle\Service\BooksManager $booksManager Books manager service
*/
    public function __construct(UsersManager $usersManager, BooksManager $booksManager)
    {
        $this->usersManager = $usersManager;
        $this->booksManager = $booksManager;
    }

    /**
     * Admin panel action
     *
     * @Route("/panel", name="admin_panel")
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function adminPanelAction(Request $request)
    {
        $searchQuery = $request->request->get('form')['search'];
        if (!$searchQuery) {
            $searchQuery = $request->query->get('search');
        }

        $reservationPage = $request->query->get('reservationPage');
        $reservations = $this->booksManager->getRepository(Reservation::class)->query($searchQuery, $reservationPage? $reservationPage : 1);

        $loansPage = $request->query->get('loansPage');
        $loans = $this->booksManager->getRepository(Loan::class)->query($searchQuery, $loansPage? $loansPage : 1);
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        $cantDeleteLastAdmin = count($this->usersManager->findUsersByRole('ROLE_ADMIN')) == 1;
        return $this->render(
            'admin/panel.html.twig',
            [
                'reservations' => $reservations,
                'loans' => $loans,
                'form' => $form->createView(),
                'search_query' => $searchQuery,
                'cant_delete_last_admin' => $cantDeleteLastAdmin
            ]
        );
    }
}
