<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Propertyad;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class PropertyAdForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('propertyRef')
            ->add('title',TextareaType::class,[
                 'constraints' => [
                    new Assert\NotBlank()
                   ]
                ])
            ->add('fees',NumberType::class,[
                 'constraints' => [
                   new Assert\NotBlank(),
                   new Assert\Positive()
                ]
            ])
            ->add('guarantee',NumberType::class,[
                 'constraints' => [
                   new Assert\NotBlank(),
                   new Assert\Positive()
                ]
            ])
            ->add('isActive',CheckboxType::class,[
               'attr'=>[
                'checked'=>'checked',
               ]
            ])
            ->add('Submit', SubmitType::class,[
              'label' => 'Create ',
         
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Propertyad::class,
        ]);
    }
}