<?php

declare(strict_types=1);

namespace App\MessengeHandler;

use App\Message\TestMessenger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestMessengerHandler
{
    public function __invoke(TestMessenger $messenger): string
    {
        return $messenger->getContent();
    }
}