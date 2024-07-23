<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;


class LoginSuccess implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('admin_dashboard'));
        } elseif (in_array('ROLE_EMPLOYE', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('employe_dashboard'));
        } elseif (in_array('ROLE_VETERINAIRE', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('veterinaire_dashboard'));
        }

        // Redirection par défaut si aucun rôle spécifique n'est trouvé
        return new RedirectResponse($this->router->generate('app_main_index'));
    }
}
