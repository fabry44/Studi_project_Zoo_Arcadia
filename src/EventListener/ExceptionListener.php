<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

class ExceptionListener
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        
        // $exception = $event->getThrowable();
        // $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        
        // $response = new Response();
        
        // if ($statusCode === 404) {
        //     $response->setContent($this->twig->render('error/404.html.twig'));
        // } else {
        //     $response->setContent($this->twig->render('error/500.html.twig', ['message' => $exception->getMessage()]));
        // }

        
        // if ($exception instanceof HttpExceptionInterface) {
        //     $response->setStatusCode($exception->getStatusCode());
        //     $response->headers->replace($exception->getHeaders());
        // } else {
        //     $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        // }

        
        // $event->setResponse($response);
    }
}
