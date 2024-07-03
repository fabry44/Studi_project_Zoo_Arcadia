<?php
// src/EventListener/LoginRedirectListener.php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoginRedirectListener
{
    private $router;
    private $tokenStorage;

    public function __construct(RouterInterface $router, TokenStorageInterface $tokenStorage)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->attributes->get('_route') !== 'app_login') {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        if ($user) {
            $roles = $user->getRoles();
            $isVerified = $user->isVerified();

            if (in_array('ROLE_ADMIN', $roles) && $isVerified === true) {
                $response = new RedirectResponse($this->router->generate('admin'));
            } elseif (in_array('ROLE_VETERINAIRE', $roles && $isVerified === true)) {
                $response = new RedirectResponse($this->router->generate('veterinaire_dashboard'));
            } elseif (in_array('ROLE_EMPLOYE', $roles  && $isVerified === true)) {
                $response = new RedirectResponse($this->router->generate('employe_dashboard'));
            } else {
                return $response = new RedirectResponse($this->router->generate('homepage'));
            }

            $request->getSession()->getFlashBag()->add('success', 'Bienvenue ' . $user->getUsername());
            $event->setResponse($response);
        }
    }
}
