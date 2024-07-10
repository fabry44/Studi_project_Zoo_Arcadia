<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Avis;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AvisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            
            TextField::new('pseudo')
                ->setDisabled(true)
                ->setLabel('Pseudonyme'),
            
            TextField::new('avis')
                ->setDisabled(true)
                ->setLabel('Avis'),
            
            AssociationField::new('employe')
                ->setLabel('Employé')
                ->setCrudController(UtilisateursCrudController::class)
                ->setDisabled(true)
                ->setTemplatePath('admin/crud/fields/links/utilisateurs_links.html.twig'),
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
}
