<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Category;
use App\Entity\Planning;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\NotNull;

class CoursesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'invalid_message' => 'choose a date',
                
                
            ])
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                
                 
            ])
            ->add('address', EntityType::class, [
                // looks for choices from this entity
                'class' => Planning::class,
                'choice_label' => 'address',
                'multiple' => false,
                'expanded' => false,
                

                 
            ])
            ->add('start_time', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text',
                'invalid_message' => 'starting time invalid',
                

            ])
            ->add('end_time',  TimeType::class, [
                'input' => 'datetime',
                'widget' => 'single_text',
                'invalid_message' => 'ending time invalid',
                
            ])
            ->add('nbr')
            
            ->add('trainer', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
               
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function(UserRepository $er) {
                    return $er->findAllTrainer();
                }  

                 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
        ]);
    }
}
