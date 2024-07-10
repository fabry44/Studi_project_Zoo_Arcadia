<?php

namespace App\Controller\Admin\Crud;

use App\Entity\AvisHabitats;
use App\Entity\Habitats;
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

class HabitatsCrudController extends AbstractCrudController
{   
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
        $dashboard = $this->extractDashboardFromPath($currentPath);

        return [
            IdField::new('id')
                ->setLabel('ID')
                ->onlyOnIndex(),

            TextField::new('nom')
                ->setLabel('Nom')
                ->setMaxLength(255)
                ->setRequired(true),
            
            TextareaField::new('descript')
                ->setLabel('Description')
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
                ->onlyOnDetail()
                ->setTemplatePath('admin/crud/fields/show/avis_habitats/show.html.twig')
                ->setLabel('Avis du Vétérinaire'),
            // ImageField::new('imgHabitats')->hideOnIndex(),
           
        ];
    }

    public function configureActions(Actions $actions): Actions
    {   
        /**
         * On crée un objet Action pour donner un avis sur un habitat.
         * L'action est associée à un bouton "Donner votre Avis" avec une icône de commentaire.
         * L'action est liée à la fonction "donnerAvis" dans le contrôleur CRUD..
         */
        $avisHabitat = Action::new('donnerAvis', 'Donner votre Avis', 'fa fa-comment')
            ->linkToCrudAction('donnerAvis')
            ->addCssClass('btn btn-info')
            ->setHtmlAttributes(['title' => 'Donner votre Avis']);
        
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

            ->add(Crud::PAGE_DETAIL, $avisHabitat)
            ->setPermission('donnerAvis', 'ROLE_VETERINAIRE')
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

        return null; // ou une valeur par défaut si nécessaire
    }
}
