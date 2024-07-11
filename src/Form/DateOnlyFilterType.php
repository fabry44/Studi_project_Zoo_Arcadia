<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateOnlyFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('date', DateType::class, [
            'widget' => 'single_text',
            'html5' => false, // Garder HTML5 activé
            'format' => 'yyyy-MM-dd', // Le format HTML5 par défaut pour les dates
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Pas de classe de données spécifique
            'csrf_protection' => true,
        ]);
    }
}
