<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Alimentations;
use App\Entity\Animaux;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
Use App\Controller\Admin\Crud\AnimauxCrudController;
use App\Entity\Utilisateurs;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\extractDashboardService;

class AlimentationsCrudController extends AbstractCrudController
{   
    private $security;
    private $entityManager;
    private $AnimauxRepository;
    private $requestStack;
    private $extractDashboardService;

    public function __construct(Security $security, EntityManagerInterface $entityManager, RequestStack $requestStack, extractDashboardService $extractDashboardService)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->AnimauxRepository = $entityManager->getRepository(Animaux::class);
        $this->requestStack = $requestStack;
        $this->extractDashboardService = $extractDashboardService;
    }
    
    public static function getEntityFqcn(): string
    {
        return Alimentations::class;
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
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy')
                ->onlyOnIndex(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy')
                ->hideOnIndex(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy')
                ->setFormTypeOptions([
                    'data' => $currentDateTime,
                    'disabled' => true,
                ])
                ->onlyWhenCreating(),

            AssociationField::new('employe')
                ->setLabel('Employé')
                ->setCustomOptions(['dashboard' => $dashboard])
                ->setCrudController(UtilisateursCrudController::class)
                ->setTemplatePath('admin/crud/fields/links/utilisateurs_links.html.twig')
                ->onlyOnIndex(),  
            TextField::new('employe')
                ->setLabel('Employé')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'data' => $loginUser,
                    'disabled' => true,
                ]),
            TextField::new('animal')
                ->setLabel('Animal')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'disabled' => true,
                ]),
            // AssociationField::new('animal')
            //     ->setLabel('Animal')
            //     ->setCrudController(AnimauxCrudController::class)

            //     ->setTemplatePath('admin/crud/fields/links/animal_links.html.twig')
            //     ,

            TextField::new('nourriture')
                ->setRequired(true)
                ->setLabel('Alimentation'), 

            NumberField::new('quantite')
                ->setRequired(true)
                ->setLabel('Quantité (g)'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Suivi des rations alimentaires des animaux')
            ->setPageTitle('new', 'Nouvelle ration Alimentation')
            ->setPageTitle('edit', 'Modifier la ration Alimentation')
            ->setPageTitle('detail', 'Détails de la ration Alimentation')
        ;
    }

    /**
     * Crée une nouvelle instance de l'entité Alimentations.
     *
     * @param string $entityFqcn Le nom de la classe de l'entité.
     * @return Alimentations La nouvelle instance de l'entité Alimentations.
     */
    public function createEntity(string $entityFqcn)
    {   //ImmutableDateTime
        $currentDateTime = new \DateTimeImmutable();
        $alimentation = new Alimentations();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $animalId = $request->query->get('animalId');
        $userId = $request->query->get('userId');

        if ($animalId) {
            $animal = $this->entityManager->getRepository(Animaux::class)->find($animalId);
            $alimentation->setAnimal($animal);
        }
        if ($userId) {
            $user = $this->entityManager->getRepository(Utilisateurs::class)->find($userId);
            $alimentation->setEmploye($user);
        }
        $alimentation->setDate($currentDateTime);

        return $alimentation;
    }
}
