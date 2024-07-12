<?php

namespace App\Controller\Admin\Crud;


use App\Entity\AvisHabitats;
use App\Entity\Habitats;
use App\Entity\ImgHabitats;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\extractDashboardService;
use App\Controller\Admin\Field\CustomImageField;


class HabitatsCrudController extends AbstractCrudController
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
        return Habitats::class;
    }

     public function configureFields(string $pageName): iterable
    {   
        
        // Récupération du chemin de l'URL
        $currentPath = $this->requestStack->getCurrentRequest();

        // Analyse du chemin pour identifier le tableau de bord
        $dashboard = $this->extractDashboardService->extractDashboardFromPath($currentPath);

        return [
            IdField::new('id')
                ->setLabel('ID')
                ->onlyOnIndex(),

            TextField::new('nom')
                ->setLabel('Nom')
                ->setRequired(true)
                ->setMaxLength(255)
                ->setRequired(true),
            
            TextareaField::new('descript')
                ->setLabel('Description')
                ->setRequired(true)
                ->setNumOfRows(10)
                ->setRequired(true)
                ->hideOnIndex(),
            // TextField::new('descript')->hideOnIndex(),

            CollectionField::new('animauxPresents')
                ->setLabel('Animaux Présents')
                ->setCustomOptions(['dashboard' => $dashboard])
                ->setTemplatePath('admin/crud/fields/links/animaux_links.html.twig')
                ->onlyOnDetail(),
            AssociationField::new('animauxPresents')
                ->setLabel('Animaux Présents')
                ->setCrudController(AnimauxCrudController::class)
                ->onlyOnIndex(),

            CollectionField::new('avisHabitats')
                ->setLabel('Avis du Vétérinaire')
                ->onlyOnIndex(),

            CollectionField::new('avisHabitats')
                ->setLabel('Avis du Vétérinaire')
                ->setEntryType(AvisHabitats::class)
                ->setTemplatePath('admin/crud/fields/show/avis_habitats/show.html.twig')
                ->onlyOnDetail(),
            
            AssociationField::new('imgHabitats')
                ->setLabel('Nombre d\'images')
                ->setCrudController(ImgHabitatsCrudController::class)
                ->onlyOnIndex(),
            CustomImageField::new('imgHabitats')
                ->setLabel('Images de l\'habitat')
                ->setBasePath('/uploads/habitats')
                ->onlyOnDetail(),
           
        ];
    }

    public function configureActions(Actions $actions): Actions
    {   
        /**
         * On crée un objet Action pour donner un avis sur un habitat.
         * L'action est associée à un bouton "Donner votre Avis".
         * L'action est liée à la fonction "donnerAvis" dans le contrôleur CRUD
         */
        $avisHabitat = Action::new('donnerAvis', 'Donner votre Avis', 'fa fa-comment')
            ->linkToCrudAction('donnerAvis')
            ->addCssClass('btn btn-primary')
            ->setHtmlAttributes(['title' => 'Donner votre Avis']);

        $ajouterPhotos = Action::new('ajouterPhotos', 'Ajouter une photo', 'fa fa-picture-o')
            ->linkToCrudAction('ajouterPhotos')
            ->addCssClass('btn btn-primary')
            ->setHtmlAttributes(['title' => 'Ajouter une photo']);
        
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ->remove(Crud::PAGE_INDEX, Action::NEW)
            
            // Mise a jours du bouton NEW
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-store')->setLabel('Nouvel habitat')->addCssClass('btn btn-primary');
            })

            // Autoriser l'action de suppression uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')

            // Autoriser l'action d'édition uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')

            ->add(Crud::PAGE_DETAIL, $avisHabitat)
            ->setPermission('donnerAvis', 'ROLE_VETERINAIRE')

            ->add(Crud::PAGE_DETAIL, $ajouterPhotos)
            ->setPermission('ajouterPhotos', 'ROLE_ADMIN')

            // ->add(Crud::PAGE_INDEX, $ajouthabitat) // TODO: changer le label des bouton
                
            // Autoriser l'action de création uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::NEW, 'ROLE_ADMIN')

        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Suivi des habitats du ZOO Arcadia')
            ->setPageTitle('new', 'Création d\'un nouvel habitat')
            ->setPageTitle('edit', 'Modifier l\'habitat')
            ->setPageTitle('detail', 'Détails de l\'habitat')
        ;
    }


    /**
     * Redirige vers le tableau de bord du vétérinaire pour donner un avis sur un habitat.
     *
     * @param AdminContext $context Le contexte administratif.
     * @return RedirectResponse La réponse de redirection vers le tableau de bord du vétérinaire.
     */
    public function donnerAvis(AdminContext $context)
    {
        $habitat = $context->getEntity()->getInstance();
        $userId = $this->getUser()->getId();  
        
        return $this->redirectToRoute('veterinaire_dashboard', [
            'crudAction' => 'new',
            'crudControllerFqcn' => AvisHabitatsCrudController::class,
            'entityFqcn' => AvisHabitats::class,
            'query' => '',
            'habitatId' => $habitat->getId(),  
            'userId' => $userId,             
        ]);
    }

    /**
     * Redirige vers la page de création d'une nouvelle image d'habitat.
     *
     * @param AdminContext $context Le contexte administratif.
     * @return RedirectResponse La réponse de redirection vers la page de création.
     */
    public function ajouterPhotos(AdminContext $context)
    {
        $habitat = $context->getEntity()->getInstance();  
        
        return $this->redirectToRoute('admin_dashboard', [
            'crudAction' => 'new',
            'crudControllerFqcn' => ImgHabitatsCrudController::class,
            'entityFqcn' => ImgHabitats::class,
            'query' => '',
            'habitatId' => $habitat->getId(),             
        ]);
    }

   
   
}
