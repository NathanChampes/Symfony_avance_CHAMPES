<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'user.email'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'user.roles',
                'choices' => [
                    'user.roles_choice.ROLE_ADMIN' => 'ROLE_ADMIN',
                    'user.roles_choice.ROLE_MANAGER' => 'ROLE_MANAGER',
                    'user.roles_choice.ROLE_USER' => 'ROLE_USER'
                ],
                'expanded' => true,
                'multiple' => true,
                'data' => $options['current_roles']
            ])
        ;

        if ($options['is_new']) {
            $builder->add('plainPassword', PasswordType::class, [
                'label' => 'user.password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new' => false,
            'current_roles' => ['ROLE_USER']
        ]);
    }
}
