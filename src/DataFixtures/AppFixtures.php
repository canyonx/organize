<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private ActivityFixtures $activityFixtures,
        private UserFixtures $userFixtures
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->activityFixtures->createActivity();
        $this->userFixtures->createAdmin();
        $this->userFixtures->createUser(3);

        $manager->flush();
    }
}
