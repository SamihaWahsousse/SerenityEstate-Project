<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class OwnerUserType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EntityType::class, [
                'class' => User::class, //'App\Entity\User',
                'by_reference' => true,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('user')
                        ->where('user.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_OWNER"%');
                },
                'label' => false,
                'placeholder' => 'choose the owner',
                'choice_label' => function ($user) {
                    return $user->getFullName() . ' -  ' . $user->getEmail();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'data_class' => User::class,
        ]);
    }
}
