<?php

namespace App\Form;

use App\Entity\PropertyType;
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
                    'placeholder' => 'choose a property type',
                    'label' => false,
                    'choice_label' => function (PropertyType $propertyType) {
                        return sprintf('(%d) %s', $propertyType->getId(), $propertyType->getName());
                    },
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
