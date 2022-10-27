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

class LoginEvent implements EventSubscriberInterface
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
                ['forcePasswordChange', 0],
            ],
        ];
    }

    public function forcePasswordChange(RequestEvent $event): void
    {

        // only deal with the main request, disregard subrequests
        if (!$event->isMainRequest()) {
            return;
        }


        // if we are visiting the password change route, no need to redirect
        if ($event->getRequest()->attributes->get('_route') == 'app_reset_password') {
            return;
        }


        $user = $this->security->getUser();
        // if you do not have a valid user, it means it's not an authenticated request, so it's not our concern
        if (!$user instanceof Partenaire) {
            return;
        }


        // if it's not their first login, and have is password changed = true, no need to redirect
        if ($user->isIsPasswordChange()) {
            return;
        }


        // if we get here, it means we need to redirect them to the password change view.
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_reset_password', [
            'id' => $user->getId(),
        ])));







    }


}