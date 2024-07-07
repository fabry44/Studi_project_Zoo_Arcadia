<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Habitats;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\Association;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class HabitatsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Habitats::class;
    }

     public function configureFields(string $pageName): iterable
    {   
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nom'),
            
            TextEditorField::new('descript')->hideOnIndex(),
            // TextField::new('descript')->hideOnIndex(),
            CollectionField::new('animauxPresents')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/animaux_links.html.twig'),
            AssociationField::new('animauxPresents')
                ->setCrudController(AnimauxCrudController::class)
                
                ->onlyOnIndex(),
            CollectionField::new('avisHabitats')
                ->onlyOnDetail(),
            AssociationField::new('avisHabitats')
                ->setCrudController(AnimauxCrudController::class)
                
                ->onlyOnIndex(),
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
}
