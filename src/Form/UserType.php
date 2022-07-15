<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
//            ->add('roles')
            ->add('password', PasswordType::class, [
                'required' => false,
                'mapped' => false
            ])
            ->add('photo', FileType::class, [
                'label' => "Select Photo : ",
                'help' => "Select file up to ~2MB with (*.png, *.jpg, *.jpeg) file extensions.",
                'mapped' => false,
                'required' => false,
            ])
            ->add('avatar', FileType::class, [
                'label' => "Select Photo : ",
                'help' => "Select file up to ~2MB with (*.png, *.jpg, *.jpeg) file extensions.",
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
