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
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options['data']);
        $builder
            ->add('description', TextareaType::class,[
                'constraints' => [
                   new Assert\NotBlank()
                ]
            ])
            ->add('isAvailable', ChoiceType::class, [
                'choices' => [
                    'Yes' => 1,
                    'No' => 0,
                ],
                'expanded' => true, // Set to true to use radio buttons
                'label'=>'Availability',
                // 'data'=>$options['data']->isAvailable()
                // 'data'=>'Yes'
                'data'=>$options['data']->isAvailable() ? $options['data']->isAvailable() : 0
            ])
            ->add('rooms',NumberType::class,[
                 'constraints' => [
                   new Assert\NotBlank(),
                   new Assert\Positive()
                ]
            ])
            ->add('area',NumberType::class,[
                'label'=>'Area (m²)',
                 'constraints' => [
                   new Assert\NotBlank(),
                   new Assert\Positive()
                ]
            ])
            ->add('price', MoneyType::class,[
               'label'=>'Price',
                'constraints' => [
                   new Assert\NotBlank(),
                   new Assert\Positive()
                ]
            ])
            ->add('createdAt', DateType::class, [
                'required' => false,
            ])
            ->add('address', AddressType::class)
            
            ->add('propertyType', PropertyTypeFormType::class, [
                 'label' => 'Maison',
                'by_reference' => true,
                'data' => $options['data']->getPropertyType()
            ]) 
            ->add('operation', OperationType::class,[
                'label'=>'Operation',
                'data' => $options['data']->getOperation()
            ])
            
            ->add('owner', OwnerUserType::class, [
                'label' => 'Select Owner',
                'by_reference' => true,
                'data'=>$options['data']->getOwner()
             ])
            ->add('Submit', SubmitType::class,[
           'label' => 'Create'
            ])
            ->add('Reset', ResetType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => Property::class,
        ]);
    }
}