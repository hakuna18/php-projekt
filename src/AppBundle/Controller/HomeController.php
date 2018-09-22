<?php
/**
 * HomeController.
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Home controller.
 *
 * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
 *
 * @return \Symfony\Component\HttpFoundation\Response HTTP Response
 */
class HomeController extends Controller
{
    /**
     * Homepage action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @Route("/", name="homepage")
     *
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function homepageAction(Request $request)
    {
        return $this->redirectToRoute('books_catalogue');
    }
}
