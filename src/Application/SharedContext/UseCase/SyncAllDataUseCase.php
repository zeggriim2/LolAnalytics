<?php

declare(strict_types=1);

namespace App\Application\SharedContext\UseCase;

use App\Application\SharedContext\Bus\CommandBusInterface;
use App\Application\Version\Command\FetchVersionsCommand;
use App\Domain\Version\Repository\VersionRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final class SyncAllDataUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly VersionRepositoryInterface $versionRepository,
        private readonly LoggerInterface $logger,
    ) {}

    public function execute(bool $forceUpdate = false)
    {
        $requestId = Uuid::v4()->toRfc4122();

        $this->logger->info('Démarrage de la synchronisation complète', [
            'request_id' => $requestId,
            'force_update' => $forceUpdate
        ]);

        try {
            $this->commandBus->dispatch(new FetchVersionsCommand($forceUpdate, $requestId));


        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la synchronisation', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
