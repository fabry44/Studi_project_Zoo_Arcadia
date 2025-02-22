<?php

namespace App\Controller\Admin\Dashboard;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use App\Entity\Utilisateurs;
use App\Entity\Avis;
use App\Entity\Services;
use App\Entity\Alimentations;
use App\Entity\RapportsVeterinaires;
use App\Entity\Animaux;
use App\Controller\Admin\Crud\UtilisateursCrudController;
use App\Controller\Admin\Crud\RapportsVeterinairesCrudController;
use App\Controller\Admin\Crud\AlimentationsCrudController;
use App\Entity\ImgServices;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYE')]
class EmployeDashboardController extends AbstractDashboardController
{
    #[Route('/employe-dashboard', name: 'employe_dashboard')]
    public function index(): Response
    {   
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $user = $this->getUser();
        $roles = $user->getRoles();
        if (in_array('ROLE_EMPLOYE', $roles)) {

            return $this->redirect($adminUrlGenerator->setController(AlimentationsCrudController::class)->generateUrl());
        }else {
            if (in_array('ROLE_ADMIN', $roles)) {

                return $this->redirect($adminUrlGenerator->setController(UtilisateursCrudController::class)->generateUrl());
            
            }else if (in_array('ROLE_VETERINAIRE', $roles)) {

                return $this->redirect($adminUrlGenerator->setController(RapportsVeterinairesCrudController::class)->generateUrl());
            
            }else {

                $this->addFlash('error', 'Vous n\'avez pas les droits pour accéder à cette page. Connectez-vous avec un compte ayant les droits nécessaires.');
                return $this->redirect('/login');
            }
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Espace Employé')
            ->setLocales(['fr']);
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToUrl('Back to the website', 'fa fa-home', '/');
        yield MenuItem::linkToCrud('Gestion des Avis', 'fa fa-user', Avis::class);
        
        yield MenuItem::subMenu('Section Service', 'fa fa-store')->setSubItems([
            MenuItem::linkToCrud('Gestion des services', 'fa fa-store', Services::class),
            MenuItem::linkToCrud('Photos ', 'fa fa-images', ImgServices::class),
        ]);  
        yield MenuItem::linkToCrud('Rations alimentaires', 'fa fa-bowl-food', Alimentations::class);
        // yield MenuItem::linkToCrud('Rapport Veterinaire ', 'fas fa-file-circle-check', RapportsVeterinaires::class)
        //     ->setPermission('ROLE_EMPLOYE');

        yield MenuItem::linkToCrud('Gestion des Animaux', 'fa fa-paw', Animaux::class);
            
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
            ->displayUserAvatar(true)
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getUserName())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                // MenuItem::linkToRoute('app_login', 'fa fa-id-card', '...', ['...' => '...']), //test
                MenuItem::linkToCrud('Profile', 'fa fa-user-cog', Utilisateurs::class)
                ->setAction('detail')
                ->setEntityId($this->getUser()->getId()),

                // MenuItem::section(),
                // MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            
            ]);
        }

        public function configureAssets(): Assets
        {
            return Assets::new()
            ->addAssetMapperEntry('app', 'admin')
            
            ;
        }
}
