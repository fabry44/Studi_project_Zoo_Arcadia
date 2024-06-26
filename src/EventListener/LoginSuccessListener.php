<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoginSuccessListener
{
    private $router;
    private $tokenStorage;

    public function __construct(RouterInterface $router, TokenStorageInterface $tokenStorage)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        $roles = $user->getRoles();
        
        // Redirection basée sur le rôle
        if (in_array('administrateur', $roles)) {
            $response = new RedirectResponse($this->router->generate('admin_dashboard'));
        } elseif (in_array('veterinaire', $roles)) {
            $response = new RedirectResponse($this->router->generate('veterinaire_dashboard'));
        } elseif (in_array('employe', $roles)) {
            $response = new RedirectResponse($this->router->generate('employe_dashboard'));
        } else {
            return;
        }

        $event->getRequest()->getSession()->getFlashBag()->add('success', 'Bienvenue ' . $user->getUsername());
        $event->setResponse($response);
    }
}
