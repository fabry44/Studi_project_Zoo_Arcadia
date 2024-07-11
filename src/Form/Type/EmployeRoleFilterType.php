<?php

// src/Form/Type/VeterinaireRoleFilterType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\UtilisateursRepository;

class EmployeRoleFilterType extends AbstractType
{
    private $utilisateursRepository;

    public function __construct(UtilisateursRepository $utilisateursRepository)
    {
        $this->utilisateursRepository = $utilisateursRepository;
    }

    /**
     * Configure les options du formulaire de filtre pour le rôle d'employé'.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $users = $this->utilisateursRepository->findAll();
        $veterinaires = [];

        foreach ($users as $user) {
            $roles = $user->getRoles();
            if (in_array('ROLE_EMPLOYE', $roles)) {
                $veterinaires[] = $user;
            }
        }

        $choices = [];
        foreach ($veterinaires as $veterinaire) {
            $choices[$veterinaire->__toString()] = $veterinaire->getId();
        }

        dump($choices); // Vérifiez ici que tous les vétérinaires sont bien récupérés

        $resolver->setDefaults([
            'choices' => $choices
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
