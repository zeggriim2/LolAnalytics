<?php

declare(strict_types=1);

namespace App\Match\Application\EventHandler;

use App\Match\Domain\Event\MatchesSavedNotification;
use Psr\Log\LoggerInterface;

final class NotifyThirdPartyOnMatchesSaved
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(MatchesSavedNotification $event): void
    {
        $this->logger->info(sprintf(
            'Notifying third party about %d matches saved for region %s',
            count($event->matchIds),
            $event->region
        ));

        $this->logger->info('Third party notified successfully');

        // Ou par un envoie de mail, SMS, etc.
    }
}
