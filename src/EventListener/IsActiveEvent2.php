<?php

namespace App\EventListener;

use App\Entity\Partenaire;
use App\Entity\Structure;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class IsActiveEvent2 implements EventSubscriberInterface
{
    private Security $security;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['isActive', 0],
            ],
        ];
    }

    public function isActive(RequestEvent $event): void
    {

        // only deal with the main request, disregard subrequests
        if (!$event->isMainRequest()) {
            return;
        }


        // if we are visiting the password change route, no need to redirect
        if ($event->getRequest()->attributes->get('_route') == 'app_login') {
            return;
        }


        $user = $this->security->getUser();
        // if you do not have a valid user, it means it's not an authenticated request, so it's not our concern
        if (!$user instanceof Structure) {
            return;
        }


        // if it's not their first login, and have is password changed = true, no need to redirect
        if ($user->isIsActive()) {
            return;
        }



        // if we get here, it means we need to redirect them to the password change view.
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_login')));









    }




}