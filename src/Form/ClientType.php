<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'form.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'form.lastname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email'
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'form.phone'
            ])
            ->add('address', TextType::class, [
                'label' => 'form.address'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
