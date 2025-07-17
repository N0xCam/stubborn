<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('image', FileType::class, [
            'label' => 'Image (JPG, PNG ou WEBP)',
            'mapped' => false,
            'required' => true,
            'constraints' => [
        new File([
            'maxSize' => '2M',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
            'mimeTypesMessage' => 'Merci d’uploader une image valide.',
        ])
    ],
])
            ->add('stockXS', NumberType::class)
            ->add('stockS', NumberType::class)
            ->add('stockM', NumberType::class)
            ->add('stockL', NumberType::class)
            ->add('stockXL', NumberType::class)
            ->add('isFeatured', CheckboxType::class, [
    'label' => 'Produit mis en avant ?',
    'required' => false,
            ]);
            
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
