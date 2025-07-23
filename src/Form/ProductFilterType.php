<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('priceRange', ChoiceType::class, [
                'choices' => [
                    'Moins de 30€' => 'less_than_30',
                    'Entre 30€ et 60€' => 'between_30_60',
                    'Plus de 60€' => 'more_than_60',
                ],
                'expanded' => true, 
                'multiple' => false,
                'label' => 'Filtrer par prix',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
