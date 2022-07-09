<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\DayTimes;
use App\Entity\Guests;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                  'placeholder' => 'contact.placeholder.name',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'contact.placeholder.email',
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'contact.placeholder.phone_number',
                ],
            ])
            ->add('guest', EntityType::class, [
                'class' => Guests::class,
                'label' => false,
                'placeholder' => "contact.placeholder.guest"
            ])
            ->add('reservation_date', DateType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'contact.placeholder.reservation_date'
                ],
            ])
            ->add('day_time', EntityType::class, [
                'class' => DayTimes::class,
                'label' => false,
                'placeholder' => 'contact.placeholder.day_time'
            ])
            ->add('msg', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'contact.placeholder.msg',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
