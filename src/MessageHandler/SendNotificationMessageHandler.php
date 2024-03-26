<?php

namespace App\MessageHandler;

use App\Message\SendNotificationMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendNotificationMessageHandler
{
    public function __invoke(SendNotificationMessage $message)
    {
        // do something with your message
    }
}
