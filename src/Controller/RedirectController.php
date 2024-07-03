<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\Security\Core\Security;

class RedirectController extends AbstractController
{   
    // private $security;

    // public function __construct(Security $security)
    // {
    //     $this->security = $security;
    // }

    #[Route('/redirect', name: 'post_login_redirect')]
    public function redirectAfterRegistration(): RedirectResponse
    {
        $user = $this->security->getUser();
        $isVerified = $this->getIsVerified();
        
        if ($isVerified === false) {
            $this->addFlash(
               'warning',
               'Veuillez vérifier votre adresse e-mail pour activer votre compte.'
            );
            return $this->redirectToRoute('/login');
        }else{
            // Redirection basée sur le rôle
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('admin');
            } elseif (in_array('ROLE_VETERINAIRE', $user->getRoles())) {
                return $this->redirectToRoute('veterinarian_dashboard');
            } elseif (in_array('ROLE_EMPLOYE', $user->getRoles())) {
                return $this->redirectToRoute('employee_dashboard');
            }
        }

        

        // Route par défaut si aucun rôle spécifique
        return $this->redirectToRoute('homepage');
    }
}
