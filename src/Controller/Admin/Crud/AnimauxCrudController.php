<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CustomImageField;
use App\Entity\Alimentations;
use App\Entity\Animaux;
use App\Entity\ImgAnimaux;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Entity\RapportsVeterinaires;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\extractDashboardService;

class AnimauxCrudController extends AbstractCrudController
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
        return Animaux::class;
    }

    public function configureFields(string $pageName): iterable
    {   
        // Récupération du chemin de l'URL
        $currentPath = $this->requestStack->getCurrentRequest();

        // Analyse du chemin pour identifier le tableau de bord
        $dashboard = $this->extractDashboardService->extractDashboardFromPath($currentPath);
        

        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('prenom')
                ->setRequired(true)
                ->setLabel('Prénom'),
            TextField::new('race')
                ->setRequired(true)
                ->setLabel('Race'),

            TextField::new('habitat')
                ->setRequired(true)
                ->setLabel('Habitat')
                ->setCustomOptions(['dashboard' => $dashboard])
                ->setTemplatePath('admin/crud/fields/links/habitats_links.html.twig'),

            // CollectionField::new('habitat')
            //     ->onlyOnDetail()
            //     ->setTemplatePath('admin/fields/habitats_links.html.twig'),
            
            AssociationField::new('rapportsVeterinaires')
                ->setLabel('Nombre de Rapports')
                ->onlyOnIndex(),
            CollectionField::new('rapportsVeterinaires')
                ->setLabel('Rapports Vétérinaires')
                ->setEntryType(RapportsVeterinaires::class)
                ->onlyOnDetail()
                ->setTemplatePath('admin/crud/fields/show/rapports_veterinaires/show.html.twig')
                ->setLabel('Rapports Vétérinaires'),

                AssociationField::new('imgAnimaux')
                    ->setLabel('Nombre d\'images')
                    ->setCrudController(ImgHabitatsCrudController::class)
                    ->onlyOnIndex(),
                CustomImageField::new('imgAnimaux')
                    ->setLabel('Images de l\'animal')
                    ->setBasePath('/uploads/animaux')
                    ->onlyOnDetail(),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {   
        // Création du bouton action "Nourrir Animal"
        $nourrirAnimal = Action::new('nourrirAnimal', 'Nourrir Animal', 'fa fa-utensils')
            ->linkToCrudAction('nourrirAnimal')
            ->addCssClass('btn btn-primary')
            ->setHtmlAttributes(['title' => 'Nourrir cet animal']);
        

            // Création du bouton action "Faire un rapport"
        $Rapport = Action::new('faireRapport', 'Faire un rapport', 'fa fa-folder-plus')
            ->linkToCrudAction('faireRapport')
            ->addCssClass('btn btn-primary')
            ->setHtmlAttributes(['title' => 'Faire un rapport']);
        
        // Création du bouton action "Ajouter une photo"
        $ajouterPhotos = Action::new('ajouterPhotos', 'Ajouter une photo', 'fa fa-picture-o')
            ->linkToCrudAction('ajouterPhotos')
            ->addCssClass('btn btn-primary')
            ->setHtmlAttributes(['title' => 'Ajouter une photo']);
      
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Supprimer l'action de suppression sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

            // Mise a jours du bouton NEW
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-paw')->setLabel('Nouvel animal')->addCssClass('btn btn-primary');
            })

            // Autoriser l'action de suppression uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')

            // Autoriser l'action d'édition uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')

            // Autoriser l'action de création uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::NEW, 'ROLE_ADMIN')

            // Supprimer l'action d'édition sur la page INDEX
            // ->remove(Crud::PAGE_INDEX, Action::EDIT)

            // Supprimer l'action d'édition sur la page DETAIL
            // ->remove(Crud::PAGE_DETAIL, Action::EDIT)

            ->add(Crud::PAGE_DETAIL, $nourrirAnimal)
            ->setPermission('nourrirAnimal', 'ROLE_EMPLOYE')
            
            ->add(Crud::PAGE_DETAIL, $Rapport)
            ->setPermission('faireRapport', 'ROLE_VETERINAIRE')

            ->add(Crud::PAGE_DETAIL, $ajouterPhotos)
            ->setPermission('ajouterPhotos', 'ROLE_ADMIN')
            
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Animaux du ZOO')
            ->setPageTitle('new', 'Ajouter un nouvel Animal')
            ->setPageTitle('edit', 'Modifier un Animal Existant')
            ->setPageTitle('detail', 'Détails de l\'Animal')
        ;
    }

    /**
     * Permet de nourrir un animal.
     *
     * @param AdminContext $context Le contexte administratif.
     * @return RedirectResponse La réponse de redirection vers le tableau de bord de l'employé.
     */
    public function nourrirAnimal(AdminContext $context)
    {
        $animal = $context->getEntity()->getInstance(); 
        $userId = $this->getUser()->getId(); 
        
        return $this->redirectToRoute('employe_dashboard', [
            'crudAction' => 'new',
            'entityFqcn' => Alimentations::class,
            'query' => '',
            'animalId' => $animal->getId(),  
            'userId' => $userId,            
        ]);
    }

    /**
     * Génère un rapport pour un animal donné et redirige vers le tableau de bord du vétérinaire.
     *
     * @param AdminContext $context Le contexte administratif.
     * @return RedirectResponse La réponse de redirection vers le tableau de bord du vétérinaire.
     */
    public function faireRapport(AdminContext $context)
    {
        $animal = $context->getEntity()->getInstance();
        $userId = $this->getUser()->getId();  
        
        return $this->redirectToRoute('veterinaire_dashboard', [
            'crudAction' => 'new',
            'crudControllerFqcn' => RapportsVeterinairesCrudController::class,
            'entityFqcn' => RapportsVeterinaires::class,
            'query' => '',
            'animalId' => $animal->getId(),  
            'userId' => $userId,             
        ]);
    }

    /**
     * Redirige vers la page de création d'une nouvelle image de l'animal.
     *
     * @param AdminContext $context Le contexte administratif.
     * @return RedirectResponse La réponse de redirection vers la page de création.
     */
    public function ajouterPhotos(AdminContext $context)
    {
        $animal = $context->getEntity()->getInstance();  
        
        return $this->redirectToRoute('admin_dashboard', [
            'crudAction' => 'new',
            'crudControllerFqcn' => ImgAnimauxCrudController::class,
            'entityFqcn' => ImgAnimaux::class,
            'query' => '',
            'animalId' => $animal->getId(),             
        ]);
    }
}
