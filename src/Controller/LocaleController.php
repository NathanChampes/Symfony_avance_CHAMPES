<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    #[Route('/change-locale/{locale}', name: 'change_locale')]
    public function changeLocale(Request $request, string $locale): Response
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);
        
        // On redirige vers la page précédente
        return $this->redirect($request->headers->get('referer') ?? '/');
    }
}
