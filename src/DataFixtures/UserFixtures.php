<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $userPasswordHasherInterface,
        private SluggerInterface $slugger
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
            ->setLastConnAt(new \DateTimeImmutable())
            ->setSlug(strtolower($this->slugger->slug($admin->getPseudo(), '_')));

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
                ->setLastConnAt(new \DateTimeImmutable())
                ->setSlug(strtolower($this->slugger->slug($user->getPseudo(), '_')));

            $this->em->persist($user);
        }
    }
}
