<?php

namespace App\Twig\Components;

use App\Entity\Trip;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class TripCard
{
    public Trip $trip;
}
