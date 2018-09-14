<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Listener responsible to change the redirection at the end of the password change
 */
class PasswordChangeListener implements EventSubscriberInterface
{
    private $router;
    private $session;

    /**
     * PasswordChangeListener constructor
     * 
     * @param Symfony\Component\Routing\Generator\UrlGeneratorInterface $router
     * 
     * @param Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(UrlGeneratorInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS  => 'onPasswordChangeSuccess',
        ];
    }

    /**
     * On Password Change Succes
     * 
     * @param FOS\UserBundle\Event\FormEvent $event
     */
    public function onPasswordChangeSuccess(FormEvent $event)
    {
        $url = $this->router->generate('fos_user_security_logout');
        $event->setResponse(new RedirectResponse($url));
    }
}
