<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('password')
            ->add('idCard')
            ->add('height')
            ->add('weight')
            ->add('trainingLevel')
            ->add('costPerHour')
            ->add('description')
            ->add('experience')
            ->add('picture')
            ->add('code')
            ->add('block')
            ->add('reports')
            ->add('membership')
            ->add('gym')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
