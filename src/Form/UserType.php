<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;




class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('fullName', TextType::class)
            ->add('photo', FileType::class, [ //we choose the field name that will contain the image profile (Field name doesn't exist in entity ) that stores the image profile
                'label' => 'User picture (only profile pictures)',
                'mapped' => false, //unmapped means that field is not associated to any entity property
                'required' => false, //no need to upload a new profile image every time we edit the user profile
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', //max size of an uploaded file in our case image profile
                        'mimeTypes' => [
                            'image/gif', //specify the image extensions to upload
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
