<?php

namespace App\Form;

use App\Entity\DayTimes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DayTimesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day_time')
            ->add('photo', FileType::class, [
                'label' => "Select Photo : ",
                'help' => "Select file up to ~2MB with (*.png, *.jpg, *.jpeg) file extensions.",
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DayTimes::class,
        ]);
    }
}
