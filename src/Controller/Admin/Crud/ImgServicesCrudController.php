<?php

namespace App\Controller\Admin\Crud;

use App\Entity\ImgServices;
use App\Entity\Services;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\extractDashboardService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ImgServicesCrudController extends AbstractCrudController
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
        return ImgServices::class;
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
            TextField::new('services')
                ->setLabel('Service')
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
                ->setBasePath('/uploads/services')
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
            // ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('new', 'Importation d\'une image pour de le service')
            ->setPageTitle('index', 'Liste des images des services')
            ->setPageTitle('detail', 'Détail de l\'image')
            ->setPageTitle('edit', 'Edition de l\'image');

        ;
    }

    public function createEntity(string $entityFqcn)
    {
        $imgServices = new ImgServices();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $serviceId = $request->query->get('serviceId');
        // $currentDateTime = new \DateTimeImmutable(); //Pas utile car pas de mise a jours de l'entité possible

        if ($serviceId) {
            $service = $this->entityManager->getRepository(Services::class)->find($serviceId);
            $imgServices->setServices($service);
        }

        return $imgServices;
    }
  
}
