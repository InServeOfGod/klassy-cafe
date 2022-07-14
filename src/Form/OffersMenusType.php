<?php

namespace App\Form;

use App\Entity\DayTimes;
use App\Entity\OffersMenus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class OffersMenusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day_time', EntityType::class, [
                'class' => DayTimes::class
            ])
            ->add('title')
            ->add('subtitle')
            ->add('price')
            ->add('cost')
            ->add('count')
            ->add('photo', FileType::class, [
                'label' => "Select Photo : ",
                'help' => "Select file up to ~2MB with (*.png, *.jpg, *.jpeg) file extensions.",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffersMenus::class,
        ]);
    }
}
