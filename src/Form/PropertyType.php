<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\PropertyTypeFormType;
use App\Form\OperationType;
use App\Form\AddressType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\OwnerUserType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class,[
                
            ])
            ->add('isAvailable', ChoiceType::class, [
                'choices' => [
                    'Yes' => 'Yes',
                    'No' => 'No',
                ],
                'expanded' => true, // Set to true to use radio buttons
                'label'=>'Availability'
            ])
            ->add('rooms')
            ->add('area',NumberType::class,[
                'label'=>'Area (m²)'
            ])
            ->add('price', NumberType::class,[
               'label'=>'Price (€)'
            ])
            ->add('createdAt', DateType::class, [
                'required' => false,
             
            ])
            ->add('address', AddressType::class)
            ->add('propertyType', PropertyTypeFormType::class, [
                'label' => 'Select property type',
                'by_reference' => true, // label for the property type field
    
            ])
            ->add('operation', OperationType::class,[
                'label'=>'Operation'
            ])
            ->add('owner', OwnerUserType::class, [
                'label' => 'Select Owner',
                'by_reference' => true,
            ])
            ->add('Submit', SubmitType::class)
            ->add('Reset', ResetType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,

        ]);
    }
}