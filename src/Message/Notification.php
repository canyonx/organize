<?php

namespace App\Message;

use App\Entity\User;

class Notification
{
    public User $to;

    public array $context;
}
