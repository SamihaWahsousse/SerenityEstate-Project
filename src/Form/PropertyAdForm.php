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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class PropertyAdForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('id')
            // ,NumberType::class,[
            //     'required'=>false
            // ])
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

        //the goal from creating this EventListener is checking the existent of the propertyAd_id field 
        //when 'the propertyAd_id field != null that means the property has already an AD so we add it in the PropertyAdForm render
        // when 'the propertyAd_id field == null that means the property has not a AD so it will be created when creating the AD Object from the PropertyAdForm
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();
            if($data->getId() != null ) 
            {
                $form->add('id');
            }
            
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Propertyad::class,
            // 'validation_groups' => ['add'] // Set a default validation group

        ]);
    }
}