<?php
// src/Acme/UserBundle/EventListener/PasswordResettingListener.php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;


class RegistrationListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => ['onRegistrationSuccess', -10],
            FOSUserEvents::REGISTRATION_COMPLETED => ['stopEvent', 9999],
            FOSUserEvents::REGISTRATION_CONFIRMED => ['stopEvent', 9999],
        ];
    }

    public function onRegistrationSuccess(FormEvent $event) {
        $url = $this->router->generate('admin_panel');
    
        $event->setResponse(new RedirectResponse($url));
    }

    public function stopEvent(FilterUserResponseEvent $event) {

        $event->stopPropagation();
    }
}