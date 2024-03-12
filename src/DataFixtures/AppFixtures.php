<?php

namespace App\DataFixtures;

use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private ActivityFixtures $activityFixtures,
        private UserFixtures $userFixtures,
        private TripFixtures $tripFixtures,
        private UserRepository $userRepository,
        private ActivityRepository $activityRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {

        $location = [
            ['Briançon', 44.8962, 6.6375],
            ['Guillestre', 44.6618, 6.6428],
            ['Embrun', 44.5680, 6.4847]
        ];


        $this->activityFixtures->createActivity();
        $this->userFixtures->createAdmin();
        $this->userFixtures->createUser(3, $location);
        $manager->flush();
        $users = $this->userRepository->findAll();
        $activities = $this->activityRepository->findAll();

        foreach ($users as $user) {

            for ($i = 0; $i < rand(2, 4); $i++) {
                $act = $activities[rand(0, count($activities) - 1)];
                $loc = $location[rand(0, count($location) - 1)];
                $this->tripFixtures->createTrip(
                    $user,
                    $act,
                    $act->getName(),
                    $loc
                );
            }
        }

        $manager->flush();
    }
}
