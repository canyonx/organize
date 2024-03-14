<?php

namespace App\EventListener;

use App\Entity\User;
use App\Entity\Setting;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
class UserListener extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    // Create settings for new user
    public function postPersist(User $user, PostPersistEventArgs $event): void
    {
        $setting = new Setting();
        $setting
            ->setMember($user)
            ->setIsFriendNewTrip(true)
            ->setIsNewMessage(true)
            ->setIsTripRequestStatusChange(true)
            ->setIsNewTripRequest(true);
        $this->em->persist($setting);
        $this->em->flush();
    }
}
