<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // return $this->render('security/login.html.twig', [
        //     'last_username' => $lastUsername,
        //     'error' => $error,
        // ]);
        return $this->render('@EasyAdmin/page/login.html.twig', [
            // parameters usually defined in Symfony login forms
            'error' => $error,
            'last_username' => $lastUsername,

            // OPTIONAL parameters to customize the login form:

            // the translation_domain to use (define this option only if you are
            // rendering the login template in a regular Symfony controller; when
            // rendering it from an EasyAdmin Dashboard this is automatically set to
            // the same domain as the rest of the Dashboard)
            'translation_domain' => 'admin',

            // by default EasyAdmin displays a black square as its default favicon;
            // use this method to display a custom favicon: the given path is passed
            // "as is" to the Twig asset() function:
            // <link rel="shortcut icon" href="{{ asset('...') }}">
            'favicon_path' => '/favicon-admin.svg',

            // the title visible above the login form (define this option only if you are
            // rendering the login template in a regular Symfony controller; when rendering
            // it from an EasyAdmin Dashboard this is automatically set as the Dashboard title)
            'page_title' => '<h1>Connexion Espace Pro</h1></br></br><h6>Cette espace est reservé au membres du zoo Arcadia. Veuillez vous connecter pour accéder à votre interface utilisateur</h6>', // the <title> of the page
            'page_' => '<p>Connectez-vous pour accéder à votre interface utilisateur</p>', // the content shown below the title

            // the string used to generate the CSRF token. If you don't define
            // this parameter, the login form won't include a CSRF token
            'csrf_token_intention' => 'authenticate',

            // the URL users are redirected to after the login (default: '/admin')
            'target_path' => $this->generateUrl('admin_dashboard'),

            // the label displayed for the username form field (the |trans filter is applied to it)
            'username_label' => 'Your username',

            // the label displayed for the password form field (the |trans filter is applied to it)
            'password_label' => 'Your password',

            // the label displayed for the Sign In form button (the |trans filter is applied to it)
            'sign_in_label' => 'Se connecter',

            // the 'name' HTML attribute of the <input> used for the username field (default: '_username')
            'username_parameter' => '_username',

            // the 'name' HTML attribute of the <input> used for the password field (default: '_password')
            'password_parameter' => '_password',

            // whether to enable or not the "forgot password?" link (default: false)
            'forgot_password_enabled' => false, //TODO: set to true quand on aura la route pour le mot de passe oublié

            // the path (i.e. a relative or absolute URL) to visit when clicking the "forgot password?" link (default: '#') TODO: add route for forgot password
            //  'forgot_password_path' => $this->generateUrl('...', ['...' => '...']), 

            // the label displayed for the "forgot password?" link (the |trans filter is applied to it)
            // 'forgot_password_label' => 'Forgot your password?',

            // whether to enable or not the "remember me" checkbox (default: false)
            'remember_me_enabled' => true,

            // remember me name form field (default: '_remember_me')
            'remember_me_parameter' => 'custom_remember_me_param',

            // whether to check by default the "remember me" checkbox (default: false)
            'remember_me_checked' => true,

            // the label displayed for the remember me checkbox (the |trans filter is applied to it)
            'remember_me_label' => 'Remember me',

            'setLocales' => "(['fr'])",
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }
}
