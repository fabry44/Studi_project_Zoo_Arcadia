<?php

// src/Filter/VeterinaireRoleFilter.php
namespace App\Filter;

use App\Form\Type\VeterinaireRoleFilterType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;

class VeterinaireRoleFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, string $label = null): self
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(VeterinaireRoleFilterType::class);
    }

    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $rapportVeterinaireId = $filterDataDto->getValue();
        dump($rapportVeterinaireId); // Vérifiez ici que l'ID est correct

        if ($rapportVeterinaireId) {
            // Assurez-vous que l'alias 'entity' correspond à celui utilisé dans votre QueryBuilder principal
            $queryBuilder->andWhere('entity.veterinaire = :veterinaireId')
                        ->setParameter('veterinaireId', $rapportVeterinaireId);
        }
    }
}
