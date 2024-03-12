<?php

namespace App\DataFixtures;

use App\Entity\Trip;
use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;

class TripFixtures
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function createTrip($user, $activity, $title, array $loc): void
    {
        $trip = new Trip();

        $trip->setMember($user)
            ->setActivity($activity)
            ->setDateAt(new \DateTimeImmutable('now + ' . rand(0, 7) . ' day'))
            ->setTitle($title)
            ->setIsAvailable(true)
            ->setLocation($loc[0])
            ->setLat($loc[1])
            ->setLng($loc[2])
            ->setDescription($title);

        $this->em->persist($trip);
    }
}
