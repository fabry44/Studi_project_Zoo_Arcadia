<?php

namespace App\Controller\Admin\Crud;

use App\Entity\AvisHabitats;
use App\Entity\Habitats;
use App\Entity\Utilisateurs;
use App\Filter\VeterinaireRoleFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Doctrine\ORM\EntityManagerInterface;


class AvisHabitatsCrudController extends AbstractCrudController
{   
    private $security;
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return AvisHabitats::class;
    }

    public function configureFields(string $pageName): iterable
    {   

        // Récupération de l'utilisateur connecté
        $loginUser = $this->security->getUser();

        // Récupération du chemin de l'URL
        $currentPath = $this->requestStack->getCurrentRequest();

        // Analyse du chemin pour identifier le tableau de bord
        $dashboard = $this->extractDashboardFromPath($currentPath);

        // Récupération de la date et de l'heure actuelles immutables
        $currentDateTime = new \DateTimeImmutable();

        return [
        IdField::new('id')->hideOnForm(),

        
        DateField::new('date')
            ->setLabel('Date')
            ->setFormat('dd-MM-yyyy HH:mm') ,
        DateField::new('date')
            ->setLabel('Date')
            ->setFormat('dd-MM-yyyy HH:mm')
            ->setFormTypeOptions([
                'data' => $currentDateTime,
                'disabled' => true,
            ])
            ->onlyWhenCreating(),
        DateField::new('date')
            ->setLabel('Date')
            ->setFormat('dd-MM-yyyy HH:mm')
            ->setFormTypeOptions([
                'disabled' => true,
            ])
            ->setPermission('ROLE_admin')
            ->onlyWhenUpdating(),
            
        // Pour la page NEW 
        AssociationField::new('habitat')
            ->setCrudController(AnimauxCrudController::class)
            ->onlyWhenCreating(),
        // Pour la page EDIT
        TextField::new('habitat')
            ->setLabel('Habitat')
            ->setFormTypeOptions([
                'disabled' => true,
            ])
            ->onlyWhenUpdating(),
        // Pour "Donner un Avis" 
        TextField::new('habitat')
            ->setLabel('Habitat')
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
                
        TextareaField::new('avis')
            ->setLabel('Avis du Vétérinaire')
            ->setNumOfRows(10)
            ->hideOnIndex(),

        

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // Supprimer l'action de suppression sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            // Supprimer l'action de suppression sur la page DETAIL
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            // Supprimer l'action d'édition sur la page DETAIL
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            // Supprimer l'action d'édition sur la page DETAIL
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('date')
            ->add(VeterinaireRoleFilter::new('veterinaire', 'Filtrer par Vétérinaire'))
            ->add('habitat')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Suivi des avis de Vétérinaire des habitats du ZOO Arcadia')
            ->setPageTitle('edit', 'Modifier l\'Avis')
            ->setPageTitle('detail', 'Détails de l\'Avis')
        ;
    }

    public function createEntity(string $entityFqcn)
    {
        $avisHabitats = new AvisHabitats();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $habitatId = $request->query->get('habitatId');
        $userId = $request->query->get('userId');

        if ($habitatId) {
            $habitat = $this->entityManager->getRepository(Habitats::class)->find($habitatId);
            $avisHabitats->setHabitat($habitat);
        }
        if ($userId) {
            $user = $this->entityManager->getRepository(Utilisateurs::class)->find($userId);
            $avisHabitats->setVeterinaire($user);
        }

        return $avisHabitats;
    }

    private function extractDashboardFromPath(string $path): ?string
    {
        $pattern = '/\/(admin|veterinaire|employe)-dashboard/';
        if (preg_match($pattern, $path, $matches)) {
            return $matches[1] . '_dashboard'; // retourne 'admin_dashboard', 'veterinaire_dashboard', etc.
        }

        return null; // ou une valeur par défaut si nécessaire
    }
    
}