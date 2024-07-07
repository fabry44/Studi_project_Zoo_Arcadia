<?php

namespace App\Controller\Admin\Crud;

use App\Entity\RapportsVeterinaires;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class RapportsVeterinairesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RapportsVeterinaires::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            
            // TextField::new('description')->hideOnIndex(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy')
                ->onlyOnIndex(),
            DateField::new('date')
                ->setLabel('Date')
                ->setFormat('dd-MM-yyyy HH:mm'),
            AssociationField::new('animal')
                ->setCrudController(AnimauxCrudController::class),
            
            AssociationField::new('veterinaire')
                ->setLabel('Vétérinaire')
                ->setCrudController(UtilisateurCrudController::class)
                ->setTemplatePath('admin/fields/veterinaire_links.html.twig'),
                
                
            TextField::new('etat')
                ->setLabel('État'),
            TextField::new('nourriture')
                ->setLabel('Nourriture'),
            NumberField::new('grammage')
                ->setLabel('Grammage (g)'),
            TextEditorField::new('detail')
                ->setLabel('Détails')
                ->hideOnIndex(),

            // ImageField::new('imgHabitats')->hideOnIndex(),

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

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('animal')
            ->add('date')
            ->add('veterinaire')
        ;
    }
}
