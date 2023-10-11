<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserCrudController extends AbstractCrudController
{
    //   add Constructor for passwordHacher
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->setEntityLabelInPlural('Users')
            ->setEntityLabelInSingular('User')
            ->setPageTitle("index", "SerenityEstate - Users administration")
            ->setPaginatorPageSize(10)
            // ->setEntityPermission('ROLE_MANAGER')
        ;
    }

    //add Menus-Optional
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email')->onlyWhenUpdating()->setDisabled(),
            EmailField::new('email')->onlyWhenCreating(),

            TextField::new('fullName'),

            Field::new('plainPassword', 'New password')
                ->hideOnIndex()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['hash_property_path' => 'password', 'label' => 'New password'],
                    'second_options' => ['label' => 'Repeat   password'],
                    'mapped' => false,
                    'invalid_message' => 'The password fields do not match.',
                    'attr' => ['autocomplete' => 'new-password'],
                    'block_name' => 'custom_password'
                ]),
            // ->setRequired($pageName === Crud::PAGE_NEW),

            ImageField::new('avatar')
                ->setBasePath('upload/avatars')
                ->setUploadDir('public/upload/avatars')
                ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]'),

            ChoiceField::new('roles')
                ->setChoices(['ROLE_MANAGER' => 'ROLE_MANAGER', 'ROLE_AGENT' => 'ROLE_AGENT', 'ROLE_CUSTOMER' => 'ROLE_CUSTOMER', 'ROLE_VIEWER' => 'ROLE_VIEWER'])
                ->allowMultipleChoices()
                ->renderAsBadges(),

            DateTimeField::new('createdAt')->onlyOnDetail(),
        ];
    }
}
