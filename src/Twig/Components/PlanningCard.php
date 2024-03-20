<?php

namespace App\Twig\Components;

use App\Entity\Trip;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class PlanningCard
{
    public Trip $trip;

    public array $status;
}
