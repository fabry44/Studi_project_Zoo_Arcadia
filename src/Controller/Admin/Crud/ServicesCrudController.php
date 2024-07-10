<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Services;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;



class ServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Services::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('nom'),            
            TextEditorField::new('descript')
                ->hideOnIndex(),
            
            // ImageField::new('imgServices')->hideOnIndex(),
           
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Autoriser l'action de Création uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::NEW, 'ROLE_AMIN')

            // // Autoriser l'action d'édition uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN ou ROLE_VETERINAIRE
            // ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            // ->setPermission(Action::EDIT, 'ROLE_VETERINAIRE')

            // Autoriser l'action de suppression uniquement pour les utilisateurs ayant le rôle ROLE_ADMIN
            ->setPermission(Action::DELETE, 'ROLE_AMIN')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Services du ZOO')
            ->setPageTitle('new', 'Ajouter un nouveau Service')
            ->setPageTitle('edit', 'Modifier un Service Existant')
            ->setPageTitle('detail', 'Détails du Service')
        ;
    }
}
