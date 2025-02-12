<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'product.name'
            ])
            ->add('character', TextType::class, [
                'label' => 'product.character'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'product.description'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'product.price',
                'currency' => 'EUR'
            ])
            ->add('size', ChoiceType::class, [
                'choices' => [
                    'choice.small' => 'small',
                    'choice.medium' => 'medium',
                    'choice.large' => 'large'
                ],
                "label" => "product.size"
            ])
            ->add('stock', NumberType::class, [
                'label' => 'product.stock'
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'message.mime_type',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
