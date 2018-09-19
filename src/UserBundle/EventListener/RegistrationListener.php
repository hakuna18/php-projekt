<?php
/**
 * RegistrationListener.
 */
namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RegistrationListener
 *  https://symfony.com/doc/master/bundles/FOSUserBundle/controller_events.html
 */
class RegistrationListener implements EventSubscriberInterface
{
    /**
     * Router
     */
    private $router;

    /**
     * Session
     */
    private $session;

    /**
     * Authorization checker
     */
    private $authChecker;

     /**
     * RegistrationListener constructor
     *
     * @param Symfony\Component\Routing\Generator\UrlGeneratorInterface                   $router
     *
     * @param Symfony\Component\HttpFoundation\Session\Session                            $session
     *
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authChecker
     */
    public function __construct(UrlGeneratorInterface $router, Session $session, AuthorizationCheckerInterface $authChecker)
    {
        $this->router = $router;
        $this->session = $session;
        $this->authChecker = $authChecker;
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

    /**
     * On Registration Success
     *
     * @param FOS\UserBundle\Event\FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        if ($this->authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            // super-admin registers admins
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_READER']);
        }

        // add confirmation msg and redirect
        $url = $this->router->generate('users');
        $this->session->getFlashBag()->add('success', 'user.registration_success');
        $event->setResponse(new RedirectResponse($url));
    }

    /**
     * Stop Event
     *
     * @param FOS\UserBundle\Event\FilterUserResponseEvent $event
     */
    public function stopEvent(FilterUserResponseEvent $event)
    {
        $event->stopPropagation();
    }
}
