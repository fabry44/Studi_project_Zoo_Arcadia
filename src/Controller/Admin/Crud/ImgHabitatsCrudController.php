<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Habitats;
use App\Entity\ImgHabitats;
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

class ImgHabitatsCrudController extends AbstractCrudController
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
        return ImgHabitats::class;
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
            TextField::new('habitat')
                ->setLabel('Habitat')
                ->setFormTypeOptions([
                    'disabled' => true,
                ]),
            
            //     ->onlyOnDetail(),
            // TextField::new('habitat')
            //     ->setLabel('Habitat')
            //     ->setCustomOptions(['dashboard' => $dashboard])
            //     ->setTemplatePath('admin/crud/fields/links/habitats_links.html.twig'),
            TextField::new('imageFile')
                ->setLabel('Image')
                ->setFormType(VichImageType::class)
                ->setRequired(true)
                ->onlyOnForms(),
            
            ImageField::new('imageName')
                ->setLabel('Image')
                ->setBasePath('/uploads/habitats')
                ->hideOnForm(),
         ];
    }

    public function configureActions(Actions $actions): Actions
    {                  
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Mise a jours du bouton NEW
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-images')->setLabel('Nouvelle Image')->addCssClass('btn btn-primary');
            })

            // Supprimer l'action de suppression sur la page INDEX
            // ->remove(Crud::PAGE_INDEX, Action::DELETE)

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
            ->setPageTitle('new', 'Importation d\'une image pour l\'habitat')
            ->setPageTitle('index', 'Liste des images des habitats')
            ->setPageTitle('edit', 'Edition de l\'image de l\'habitat')
            ->setPageTitle('detail', 'Détails de l\'image de l\'habitat');
        ;
    }
    

    public function createEntity(string $entityFqcn)
    {
        $imgHabitats = new ImgHabitats();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $habitatId = $request->query->get('habitatId');
        // $currentDateTime = new \DateTimeImmutable();

        if ($habitatId) {
            $habitat = $this->entityManager->getRepository(Habitats::class)->find($habitatId);
            $imgHabitats->setHabitat($habitat);
        }

        return $imgHabitats;
    }
   
}
