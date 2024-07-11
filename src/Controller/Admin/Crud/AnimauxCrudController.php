<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Alimentations;
use App\Entity\Animaux;
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

class AnimauxCrudController extends AbstractCrudController
{   
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
        $dashboard = $this->extractDashboardFromPath($currentPath);
        

        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('prenom')
                ->setLabel('Prénom'),
            TextField::new('race')
                ->setLabel('Race'),

            TextField::new('habitat')
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

        ];
    }

    public function configureActions(Actions $actions): Actions
    {   
        // Création de l'objet action "Nourrir Animal"
        $nourrirAnimal = Action::new('nourrirAnimal', 'Nourrir Animal', 'fa fa-utensils')
            ->linkToCrudAction('nourrirAnimal')
            ->addCssClass('btn btn-info')
            ->setHtmlAttributes(['title' => 'Nourrir cet animal']);
        

            // Création de l'objet action "Faire un rapport"
        $Rapport = Action::new('faireRapport', 'Faire un rapport', 'fa fa-folder-plus')
            ->linkToCrudAction('faireRapport')
            ->addCssClass('btn btn-info')
            ->setHtmlAttributes(['title' => 'Faire un rapport']);
      
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Supprimer l'action de suppression sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

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
     * Extrait le tableau de bord à partir du chemin donné.
     *
     * @param string $path Le chemin à analyser.
     * @return string|null Le tableau de bord extrait ou null si aucun tableau de bord n'est trouvé.
     */
    private function extractDashboardFromPath(string $path): ?string
    {
        $pattern = '/\/(admin|veterinaire|employe)-dashboard/';
        if (preg_match($pattern, $path, $matches)) {
            return $matches[1] . '_dashboard'; // retourne 'admin_dashboard', 'veterinaire_dashboard', etc.
        }
    }

}
