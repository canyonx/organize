<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $userPasswordHasherInterface
    ) {
    }

    public function createAdmin(): void
    {
        $admin = new User;
        $admin->setEmail('admin@gmail.com')
            ->setPseudo('Admin')
            ->setPassword($this->userPasswordHasherInterface->hashPassword($admin, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setLastConnAt(new \DateTimeImmutable());

        $this->em->persist($admin);
    }

    public function createUser(int $qty): void
    {
        for ($i = 0; $i < $qty; $i++) {
            $user = new User;
            $user->setEmail('user' . $i . '@gmail.com')
                ->setPseudo('user' . $i)
                ->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'user'))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setLastConnAt(new \DateTimeImmutable());

            $this->em->persist($user);
        }
    }
}
