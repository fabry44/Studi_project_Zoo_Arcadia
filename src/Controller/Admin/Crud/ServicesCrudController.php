<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CustomImageField;
use App\Entity\ImgServices;
use App\Entity\Services;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\extractDashboardService;

class ServicesCrudController extends AbstractCrudController
{   
    private $requestStack;
    private $extractDashboardService;  

    public function __construct(RequestStack $requestStack, extractDashboardService $extractDashboardService)
    {
        $this->requestStack = $requestStack;
        $this->extractDashboardService = $extractDashboardService;
    }

    public static function getEntityFqcn(): string
    {
        return Services::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setLabel('Id')
                ->onlyOnIndex(),
            TextField::new('nom')
                ->setRequired(true)
                ->setLabel('Nom'),            
            TextEditorField::new('descript')
                ->setRequired(true)
                ->setLabel('Descrition')
                ->hideOnIndex(),
            
            AssociationField::new('imgServices')
                    ->setLabel('Nombre d\'images')
                    ->setCrudController(ImgServicesCrudController::class)
                    ->onlyOnIndex(),
            CustomImageField::new('imgServices')
                ->setLabel('Images du service')
                ->setBasePath('/uploads/services')
                ->onlyOnDetail(),
           
        ];
    }

    public function configureActions(Actions $actions): Actions
    {   
        // Création du bouton action "Ajouter une photo"
        $ajouterPhotos = Action::new('ajouterPhotos', 'Ajouter une photo', 'fa fa-picture-o')
            ->linkToCrudAction('ajouterPhotos')
            ->addCssClass('btn btn-primary')
            ->setHtmlAttributes(['title' => 'Ajouter une photo']);

        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Autoriser l'action de Création uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            

            // Mise a jours du bouton NEW
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-store')->setLabel('Nouveau service')->addCssClass('btn btn-primary');
            })         

            // Autoriser l'action de suppression uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            // Autoriser l'action de Création uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::NEW, 'ROLE_ADMIN')

            ->add(Crud::PAGE_DETAIL, $ajouterPhotos)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Services du ZOO')
            ->setPageTitle('new', 'Ajouter un nouveau Service')
            ->setPageTitle('edit', 'Modifier un Service Existant')
            ->setPageTitle('detail', 'Détails du Service')
        ;
    }

    /**
     * Redirige vers la page de création d'une nouvelle image de service.
     *
     * @param AdminContext $context Le contexte administratif.
     * @return RedirectResponse La réponse de redirection vers la page de création.
     */
    public function ajouterPhotos(AdminContext $context)
    {   
        //Récupération du le service
        $service = $context->getEntity()->getInstance();
        
        // Récupération du chemin de l'URL
        $currentPath = $this->requestStack->getCurrentRequest();

        // Analyse du chemin pour identifier le tableau de bord
        $dashboard = $this->extractDashboardService->extractDashboardFromPath($currentPath);
        
        return $this->redirectToRoute( $dashboard, [
            'crudAction' => 'new',
            'crudControllerFqcn' => ImgServicesCrudController::class,
            'entityFqcn' => ImgServices::class,
            'query' => '',
            'serviceId' => $service->getId(),             
        ]);
    }
}
