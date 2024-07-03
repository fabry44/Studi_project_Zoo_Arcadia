<?php

namespace App\Controller\Admin\Dashboard;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\Crud\UtilisateursCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Utilisateurs;
use App\Entity\Animaux;
use App\Entity\Habitats;
use App\Entity\Services;
use App\Entity\RapportsVeterinaires;
use App\Entity\Alimentations;
use App\Entity\Avis;
use App\Entity\AvisHabitats;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin-dashbord', name: 'admin_dashboard')]
    public function index(): Response
    {   
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {

            return $this->redirect($adminUrlGenerator->setController(UtilisateursCrudController::class)->generateUrl());
        
        }else {
            if (in_array('ROLE_EMPLOYE', $roles)) {

                return $this->redirect($adminUrlGenerator->setController(AlimentationsCrudController::class)->generateUrl());
            
            }else if (in_array('ROLE_VETERINAIRE', $roles)) {

                return $this->redirect($adminUrlGenerator->setController(RapportsVeterinairesCrudController::class)->generateUrl());
            
            }else {

                this->addFlash('error', 'Vous n\'avez pas les droits pour accéder à cette page. Connectez-vous avec un compte ayant les droits nécessaires.');
                return $this->redirect('app_login');
            }
        }
       


        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        //Option 2. You can make your dashboard redirect to different pages depending on the user
        
        // if ('ROLE_ADMIN' === $this->getUser()->getRoles()) {

        //     return $this->redirect('admin');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Espace Administrateur');
            // ->renderSidebarMinimized();
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToUrl('Back to the website', 'fa fa-home', '/');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Utilisateurs::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Services', 'fa fa-store', Services::class);
        yield MenuItem::linkToCrud('Habitats', 'fa fa-leaf', Habitats::class);
        yield MenuItem::linkToCrud('Animaux', 'fa fa-paw', Animaux::class);
        yield MenuItem::linkToCrud('Rapport Veterinaire ', 'fas fa-file-circle-check', RapportsVeterinaires::class);
    }
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getPrenom() . ' ' . $user->getNom())
            // use this method if you don't want to display the name of the user
            ->displayUserName(true)

            // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            // ->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            ->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getUserName())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                // MenuItem::linkToRoute('app_login', 'fa fa-id-card', '...', ['...' => '...']), //test
                MenuItem::linkToCrud('Profile', 'fa fa-user-cog', Utilisateurs::class)
                ->setAction('detail')
                ->setEntityId($this->getUser()->getId()),

                MenuItem::section(),
                // MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }
}
