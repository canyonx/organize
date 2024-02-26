<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;

class ActivityFixtures
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function createActivity(): void
    {
        $activities = [
            'vtt', 'kayak', 'ski', 'escalade', 'canyoning',
            'parapente', 'randonnée', 'balade', 'trail', 'vélo de route',
            'mécanique', 'ski de randonnée', 'alpinisme', 'tennis', 'natation',
            'belotte', 'jeux de société', 'musique'
        ];

        foreach ($activities as $a) {
            $activity = new Activity;
            $activity->setName($a);

            $this->em->persist($activity);
        }
    }
}
