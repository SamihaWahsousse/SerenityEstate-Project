<?php

namespace App\Form;

use App\Entity\PropertyType;
use App\Repository\OperationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PropertyTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                EntityType::class,
                [
                    'class' => PropertyType::class,
                    'by_reference' => true,
                    'placeholder' => 'Select property type ',
                    'label' => false,
                    'choice_label' => function (PropertyType $propertyType) {
                        return sprintf($propertyType->getName());
                    }, 
                    'data' => $options['data']
                ]
                
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertyType::class,
        ]);
    }
}