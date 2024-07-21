<?php

// src/Controller/Admin/AnimalCrudController.php
namespace App\Controller\Admin\Crud;

use App\Document\Animal;
use Doctrine\ODM\MongoDB\DocumentManager;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

class VueAnimalCrudController extends AbstractCrudController
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('prenom', 'Prénom'),
            IntegerField::new('vue', 'Nombre de vues'),
        ];
    }

    
}
