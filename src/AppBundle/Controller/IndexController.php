<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Index controller.
 * 
 * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
 * 
 * @return \Symfony\Component\HttpFoundation\Response HTTP Response
 */
class IndexController extends Controller
{
    /**
     * Index action.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * 
     * @Route("/", name="homepage")
     * 
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('books_catalogue');
    }
}
