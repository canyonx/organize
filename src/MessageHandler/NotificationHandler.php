<?php

namespace App\MessageHandler;

use App\Message\Notification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NotificationHandler
{
    public function __invoke(Notification $notification)
    {
        dump($notification);
    }
}
