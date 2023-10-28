<?php

namespace App\Form;

use App\Entity\Operation;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
                'name',
                EntityType::class,
                [
                    'class' => Operation::class,
                    'label' => false,
                    'placeholder' => 'Select operation',
                   

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}