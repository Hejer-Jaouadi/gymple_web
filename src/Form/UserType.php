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
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('password')
            ->add('id_card')
            ->add('height')
            ->add('weight')
            ->add('training_level')
            ->add('cost_per_hour')
            ->add('description')
            ->add('experience')
            ->add('picture')
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
