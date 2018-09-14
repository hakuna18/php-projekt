<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;

// https://symfony.com/doc/master/bundles/FOSUserBundle/controller_events.html
class PasswordChangeListener implements EventSubscriberInterface
{
    private $router;
    private $session;

    public function __construct(UrlGeneratorInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS  => 'onPasswordChangeSuccess',
        ];
    }

    public function onPasswordChangeSuccess(FormEvent $event)
    {
        $url = $this->router->generate('fos_user_security_logout');
        $event->setResponse(new RedirectResponse($url));
    }
}
