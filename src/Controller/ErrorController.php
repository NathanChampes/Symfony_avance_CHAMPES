<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ErrorController extends AbstractController
{
    public function show(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if ($exception instanceof NotFoundHttpException) {
            $response = $this->render('error/404.html.twig', [
                'message' => $exception->getMessage()
            ]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }
}