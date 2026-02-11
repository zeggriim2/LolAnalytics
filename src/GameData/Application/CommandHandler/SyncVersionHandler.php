<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncVersionCommand;
use App\GameData\Application\Port\RiotVersionProviderInterface;
use App\GameData\Domain\Model\Version;
use App\GameData\Domain\Repository\VersionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncVersionHandler
{
    public function __construct(
        private readonly RiotVersionProviderInterface $versionProvider,
        private readonly VersionRepositoryInterface $versionRepository,
    ) {
    }

    public function __invoke(SyncVersionCommand $command): void
    {
        $dtos = $this->versionProvider->fetchVersions();

        foreach (array_reverse($dtos) as $dto) {
            $version = new Version(
                $dto->version,
            );
            $this->versionRepository->save($version);
        }
    }
}
