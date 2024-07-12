<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Animaux;
use App\Entity\ImgAnimaux;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\extractDashboardService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class ImgAnimauxCrudController extends AbstractCrudController
{   
    private $requestStack;
    private $entityManager;
    private $extractDashboardService;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, extractDashboardService $extractDashboardService)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->extractDashboardService = $extractDashboardService;
    }

    public static function getEntityFqcn(): string
    {
        return ImgAnimaux::class;
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
            TextField::new('animal')
                ->setLabel('Animal')
                ->setFormTypeOptions([
                    'disabled' => true,
                ]),
            TextField::new('imageFile')
                ->setLabel('Image')
                ->setFormType(VichImageType::class)
                ->setRequired(true)
                ->onlyOnForms(),
            
            ImageField::new('imageName')
                ->setLabel('Image')
                ->setBasePath('/uploads/animaux')
                ->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {                  
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Supprimer l'action de suppression sur la page INDEX
            // ->remove(Crud::PAGE_INDEX, Action::DELETE)

            // Mise a jours du bouton NEW
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-images')->setLabel('Nouvelle Image')->addCssClass('btn btn-primary');
            })

            // Autoriser l'action de suppression uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')

            // Autoriser l'action d'édition uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')

            // Autoriser l'action de création uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::NEW, 'ROLE_ADMIN')

            
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('new', 'Importation d\'une image pour de l\'animal')
            ->setPageTitle('index', 'Liste des images des animaux')
            ->setPageTitle('edit', 'Edition de l\'image de l\'animal')
            ->setPageTitle('detail', 'Détail de l\'image de l\'animal');
        ;
    }

    public function createEntity(string $entityFqcn)
    {
        $imgAnimaux = new ImgAnimaux();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $animalId = $request->query->get('animalId');
        // $currentDateTime = new \DateTimeImmutable(); //Pas utile car pas de mise a jours de l'entité possible

        if ($animalId) {
            $Animal = $this->entityManager->getRepository(Animaux::class)->find($animalId);
            $imgAnimaux->setAnimal($Animal);
        }

        return $imgAnimaux;
    }

    
   
}
