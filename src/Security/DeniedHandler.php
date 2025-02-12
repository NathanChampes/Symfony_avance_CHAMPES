<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;

class DeniedHandler implements AccessDeniedHandlerInterface
{
    private $requestStack;
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack
    ) {
        $this->requestStack = $requestStack;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        //J'ai eu pleins de problèmes parce qu'ils ont changé la manière de gérer les sessions

        //Maintenant il faut passer par requestStack et donc j'ai eu du mal à trouver comment faire
        //J'ai trouvé cette solution sur stackoverflow
        // https://stackoverflow.com/questions/75851138/cannot-autowire-service-app-controller-maincontroller-argument-session-of
        $session = $this->requestStack->getSession();
        if($session instanceof Session) {
            $flashbag = $session->getFlashBag();
        }
        $flashbag->add('danger', 'message.access_denied');

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }
}
