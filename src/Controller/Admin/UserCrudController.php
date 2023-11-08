<?php

namespace App\Controller\Admin;

use App\Controller\ResetPasswordController;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Routing\RouterInterface;

use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;


class UserCrudController extends AbstractCrudController
{
    use ResetPasswordControllerTrait;
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
        $sendEmailFirstAccess = Action::new('sendEmailFirstAccess')
            ->linkToCrudAction('sendEmailFirstAccess');

        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $sendEmailFirstAccess);
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
                ])
                ->setRequired($pageName === Crud::PAGE_NEW),

            ImageField::new('avatar')
                ->setBasePath('upload/avatars')
                ->setUploadDir('public/upload/avatars')
                ->setUploadedFileNamePattern('[slug]-[randomhash].[extension]'),

            ChoiceField::new('roles')
                ->setChoices(['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_MANAGER' => 'ROLE_MANAGER', 'ROLE_AGENT' => 'ROLE_AGENT', 'ROLE_CLIENT' => 'ROLE_CLIENT', 'ROLE_OWNER' => 'ROLE_OWNER', 'ROLE_VIEWER' => 'ROLE_VIEWER'])
                ->allowMultipleChoices()
                ->renderAsBadges(),

            DateTimeField::new('createdAt')->onlyOnDetail(),
            TextField::new('phoneNumber'),
            BooleanField::new('isActive'),
        ];
    }

    //function that retreive the id of the selected user and his email to send a reset email 
    public function sendEmailFirstAccess(AdminContext $context, ResetPasswordController $resetPassword,AdminUrlGenerator $adminUrlGenerator, MailerInterface $mailer, RouterInterface $router, string $token = null)
    {
        $userObject = $context->getEntity()->getInstance();
        $useremail = $userObject->getEmail();
        $phoneNumber = $userObject->getphoneNumber();
        $userFullName = $userObject->getFullName();
        
        if ($token) {
            $this->storeTokenInSession($token);
        }
        
        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }
  
        $resetUrl = $router->generate('app_reset_password', ['token' => $token], RouterInterface::ABSOLUTE_URL);

        
        $email = (new TemplatedEmail())   //send email to selected user 
            ->from('no-reply@serenity-estate.com')
            ->to($useremail)
            ->subject('First Access - SerenityEstate Application ')
            ->htmlTemplate('mailer/mailFirstUserAccess.html.twig')
            ->context([
                'userEmail' => $useremail,
                'userFullName' =>$userFullName, // Pass the user to the email template
                'phoneNumber'=>$phoneNumber,
                 'resetUrl' => $resetUrl, // Pass the first access URL to the email template

            ]);
            
        $mailer->send($email);// Send the email to the user with the reset URL.

        //redirect to index USER CRUD 
        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();
        return $this->redirect($targetUrl);



    }

}