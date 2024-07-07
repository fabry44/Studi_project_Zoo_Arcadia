<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Animaux;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\Association;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AnimauxCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animaux::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('prenom')
                ->setLabel('Prénom'),
            TextField::new('race')
                ->setLabel('Race'),

            TextField::new('habitat')
                ->setLabel('Habitat')
                ->setTemplatePath('admin/fields/habitats_links.html.twig')
                ->onlyOnDetail(),

            TextField::new('habitat')
                ->setLabel('Habitat')
                ->onlyOnIndex(),

            // CollectionField::new('habitat')
            //     ->onlyOnDetail()
            //     ->setTemplatePath('admin/fields/habitats_links.html.twig'),
            
            AssociationField::new('rapportsVeterinaires')
                ->setLabel('Nombre de Rapports')
                ->onlyOnIndex(),
            CollectionField::new('rapportsVeterinaires')
                ->setLabel('Rapports Vétérinaires')
                ->setEntryType(RapportsVeterinaires::class)
                ->onlyOnDetail()
                ->setTemplatePath('admin/crud/rapports_veterinaires/show.html.twig')
                ->setLabel('Rapports Vétérinaires'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
        ;
    }
}
