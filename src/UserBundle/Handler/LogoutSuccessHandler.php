<?php
// src/UserBundle/Handler/LogoutSuccessHandler.php
namespace UserBundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class LogoutSuccessHandler
 */
class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    protected $router;
    protected $session;

    /**
     * LogoutSuccessHandler constructor
     *
     * @param Symfony\Component\Routing\Generator\UrlGeneratorInterface $router
     *
     * @param Symfony\Component\HttpFoundation\Session\Session          $session
     */
    public function __construct(UrlGeneratorInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * On Logout Success
     *
     * @param Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse Redirect Response
     */
    public function onLogoutSuccess(Request $request)
    {
        $this->session->getFlashBag()->add('success', 'confirmation.logged_out');
        $route = $this->router->generate('fos_user_security_login');

        return new RedirectResponse($route);
    }
}
