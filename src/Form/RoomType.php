<?php

namespace App\Form;

use App\Entity\Gym;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roomname')
            ->add('roomnumber')
            ->add('max_nbr')
            ->add('idgym', EntityType::class, [
                'class' => Gym::class,
                'choice_label' => 'location',
                'multiple' => false,
                'expanded' => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
