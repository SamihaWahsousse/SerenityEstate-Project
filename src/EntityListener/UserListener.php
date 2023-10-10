<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{

    public function __construct(private UserPasswordHasherInterface $hash)
    {
    }


    public function prePersist(User $user)
    {
        $this->encodePassword($user);
    }


    //for each password modification we hash the user password
    public function preUpdate(User $user)
    {
        $this->encodePassword($user);
    }


    /**
     * Encode password based on plain password
     * @param User $user
     * @return void
     */
    public function encodePassword(User $user): void
    {
        if ($user->getPlainPassword()) {

            $user->setPassword(
                $this->hash->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );
        }
    }
}
