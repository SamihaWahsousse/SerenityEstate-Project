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
        $builder->add('users', EntityType::class, [
            'class' => User::class, //'App\Entity\User',
            'query_builder' => function (EntityRepository $entityRepository) {
                return $entityRepository->createQueryBuilder('user')
                    ->where('user.roles LIKE :role')
                    ->setParameter('role', '%"ROLE_OWNER"%');
            },
            'label' => false,
            // 'mapped' => false,
            'choice_label' => 'email', //we can use any property from the User entity to display
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
        ]);
    }
}