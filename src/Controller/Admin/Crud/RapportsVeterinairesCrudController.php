<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Animaux;
use App\Entity\RapportsVeterinaires;
use App\Entity\Utilisateurs;
use App\Filter\VeterinaireRoleFilter;
use App\Form\Type\VeterinaireRoleFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\extractDashboardService;

class RapportsVeterinairesCrudController extends AbstractCrudController
{   
    private $security;
    private $requestStack;
    private $entityManager;
    private $UtilisateursRepository;
    private $extractDashboardService;

    public function __construct(RequestStack $requestStack, Security $security, EntityManagerInterface $entityManager, extractDashboardService $extractDashboardService)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->UtilisateursRepository = $entityManager->getRepository(Utilisateurs::class);
        $this->extractDashboardService = $extractDashboardService;
    }

    public static function getEntityFqcn(): string
    {
        return RapportsVeterinaires::class;
    }

    public function configureFields(string $pageName): iterable
    {      
        // Récupération de l'utilisateur connecté
        $loginUser = $this->security->getUser();

        // Récupération du chemin de l'URL
        $currentPath = $this->requestStack->getCurrentRequest();

        // Analyse du chemin pour identifier le tableau de bord
        $dashboard = $this->extractDashboardService->extractDashboardFromPath($currentPath);

        // Récupération de la date et de l'heure actuelles immutables
        $currentDateTime = new \DateTimeImmutable();
        

        return [
            IdField::new('id')->hideOnForm(),

            
            // TextField::new('description')->hideOnIndex(),
            // DateField::new('date')
            //     ->setLabel('Date')
            //     ->setFormat('dd-MM-yyyy')
            //     ->onlyOnIndex(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy') 
                ->onlyOnIndex(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy')
                ->setFormTypeOptions([
                    'data' => $currentDateTime,
                    'disabled' => true,
                ])
                ->onlyWhenCreating(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy')
                ->setFormTypeOptions([
                    'disabled' => true,
                ])
                ->onlyWhenUpdating(),
            
            // Pour la page EDIT
            TextField::new('animal')
                ->setLabel('Animal')
                ->setFormTypeOptions([
                    'disabled' => true,
                ])
                ->onlyWhenUpdating(),  
            // Pour la page NEW  
            // AssociationField::new('animal')
            //     ->setLabel('Animal')
            //     ->setCrudController(AnimauxCrudController::class)
            //     ->onlyWhenCreating(),
            // Pour Faire Rapport    
            TextField::new('Animal')
            ->setLabel('Animal')
            ->setFormTypeOptions([
                'disabled' => true,
            ])
            ->setPermission('ROLE_VETERINAIRE'),
            
            AssociationField::new('veterinaire')
                ->setLabel('Vétérinaire')
                ->setCustomOptions(['dashboard' => $dashboard])
                ->setCrudController(UtilisateursCrudController::class)
                ->setTemplatePath('admin/crud/fields/links/utilisateurs_links.html.twig')
                ->onlyOnIndex(),  
            TextField::new('veterinaire')
                ->setLabel('Vétérinaire')
                ->setFormTypeOptions([
                    'data' => $loginUser,
                    'disabled' => true,
                ])
                ->onlyWhenCreating(),              
                
            TextField::new('etat')
                ->setLabel('État')
                ->setRequired(true),

            TextField::new('nourriture')
                ->setLabel('Nourriture')
                ->setRequired(true),

            NumberField::new('grammage')
                ->setLabel('Grammage (g)')
                ->setRequired(true),

            TextAreaField::new('detail')
                ->setLabel('Détails')
                ->setNumOfRows(10)
                ->hideOnIndex(),

            // ImageField::new('imgHabitats')->hideOnIndex(),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {   
        
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Mise a jours du bouton NEW
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-file-circle-check')->setLabel('Nouveau rapport')->addCssClass('btn btn-primary');
            })

            // Supprimer l'action de suppression sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

            // Supprimer l'action de suppression sur la page DETAIL
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)

            // Supprimer l'action d'édition sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::EDIT)

            // Supprimer l'action d'édition sur la page DETAIL
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)

            // Supprimer l'action de Création sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::NEW)

            // Autoriser l'action de Création  uniquement pour les utilisateurs ayant le rôle ROLE_VETERINAIRE
            ->setPermission(Action::NEW, 'ROLE_VETERINAIRE')            

        ;
    }

    public function configureFilters(Filters $filters): Filters
    {   
        
        return $filters
            ->add('animal')
            ->add('date') 
            ->add(VeterinaireRoleFilter::new('veterinaire', 'Filtrer par Vétérinaire'))
            
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Suivi des rapports vétérinaires')
            ->setPageTitle('new', 'Nouveau rapport vétérinaire')
            ->setPageTitle('edit', 'Modifier le rapport vétérinaire')
            ->setPageTitle('detail', 'Détails du rapport vétérinaire')
        ;
    }

    /**
     * Crée une nouvelle entité RapportsVeterinaires.
     *
     * @param string $entityFqcn Le nom complet de la classe de l'entité à créer.
     * @return RapportsVeterinaires La nouvelle entité RapportsVeterinaires créée.
     */
    public function createEntity(string $entityFqcn)
    {
        $rapportVeterinaire = new RapportsVeterinaires();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $animalId = $request->query->get('animalId');
        $userId = $request->query->get('userId');
        $currentDateTime = new \DateTimeImmutable();

        if ($animalId) {
            $animal = $this->entityManager->getRepository(Animaux::class)->find($animalId);
            $rapportVeterinaire->setAnimal($animal);
        }
        if ($userId) {
            $user = $this->entityManager->getRepository(Utilisateurs::class)->find($userId);
            $rapportVeterinaire->setVeterinaire($user);
        }
        $rapportVeterinaire->setDate($currentDateTime);

        return $rapportVeterinaire;
    }
}
