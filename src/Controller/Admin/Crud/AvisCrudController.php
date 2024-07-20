<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Avis;
use App\Entity\Utilisateurs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\HttpFoundation\RequestStack;

class AvisCrudController extends AbstractCrudController
{   
    private $security;
    private $entityManager;
    private $requestStack;

    public function __construct(Security $security, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }

    public function configureFields(string $pageName): iterable
    {   
        // Récupération de l'utilisateur connecté
        $loginUser = $this->security->getUser();

        return [
            IdField::new('id')->hideOnForm(),

            
            TextField::new('pseudo')
                ->setDisabled(true)
                ->setLabel('Pseudonyme'),
            
            NumberField::new('rating')
                ->setDisabled(true)
                ->setLabel('Note'),
            
            TextField::new('avis')
                ->setDisabled(true)
                ->setLabel('Avis'),
            
            AssociationField::new('employe')
                ->setLabel('Employé')
                ->setCrudController(UtilisateursCrudController::class)
                ->setDisabled(true)
                ->setTemplatePath('admin/crud/fields/links/utilisateurs_links.html.twig')
                ->onlyOnIndex(), 
            TextField::new('employe')
                ->setLabel('Employé')
                ->setFormTypeOptions([
                    'data' => $loginUser,
                    'disabled' => true,
                ])
                ->onlyWhenUpdating(),  
            BooleanField::new('valide')
                ->setLabel('Publié')
                ->renderAsSwitch(true)
                ->onlyWhenUpdating(),
            BooleanField::new('valide')
                ->setLabel('Publié')
                ->renderAsSwitch(false),
                
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
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Avis Clients')
            ->setPageTitle('detail', 'Détails de l\'Avis')
            ->setPageTitle('new', 'Nouvel Avis')
            ->setPageTitle('edit', 'Validation l\'Avis')
            
        ;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->security->getUser();

        if ($user) {
            $entityInstance->setEmploye($user);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->security->getUser();
        $userId = $user->Utilisateurs->getId();

        if ($userId) {
            $entityInstance->setEmploye($userId);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function createEntity(string $entityFqcn)
    {
        $Avis = new Avis();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $user = $this->security->getUser();
        $userId = $request->query->get('userId');

        if ($user) {
            
            $Avis->setEmploye($user);
        }

        return $Avis;
    }
}
