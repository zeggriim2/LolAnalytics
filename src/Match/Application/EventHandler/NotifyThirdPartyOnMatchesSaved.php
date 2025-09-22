<?php

declare(strict_types=1);

namespace App\Match\Application\EventHandler;

use App\Match\Domain\Event\MatchesSavedNotification;
use Psr\Log\LoggerInterface;

final class NotifyThirdPartyOnMatchesSaved
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $thirdPartyWebhookUrl // injecté depuis parameters.yaml ou env
    ) {}

    public function __invoke(MatchesSavedNotification $event): void
    {
        $this->logger->info(sprintf(
            "Notifying third party about %d matches saved for region %s",
            count($event->matchIds),
            $event->region
        ));

        $payload = [
            'matches' => $event->matchIds,
            'region' => $event->region,
            'timestamp' => $event->occurredAt->format(DATE_ATOM)
        ];

        // $this->httpClient->request('POST', $this->thirdPartyWebhookUrl, ['json' => $payload]);

        $this->logger->info("Third party notified successfully");

        // Ou par un envoie de mail, SMS, etc.
    }
}
