<?php

namespace App\Form;

use App\Entity\Avis;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;


class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le pseudo est obligatoire']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le pseudo ne doit pas dépasser 255 caractères',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('rating', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La note est obligatoire',
                    ]),
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'La note doit être comprise entre {{ min }} et {{ max }}',
                    ]),
                ],
            ])
            ->add('avis', TextareaType::class, [
                'label' => 'Votre avis',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'avis est obligatoire']),
                    new Assert\Length([
                        'max' => 500,
                        'maxMessage' => 'L\'avis ne doit pas dépasser 500 caractères',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
