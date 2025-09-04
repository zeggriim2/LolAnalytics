<?php

declare(strict_types=1);

namespace App\Application\Version\Command;

use App\Domain\Version\Repository\VersionRepositoryInterface;
use App\Domain\Version\Service\VersionServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class FetchVersionsCommandHandler
{
    public function __construct(
        private readonly VersionServiceInterface $versionService,
        private readonly VersionRepositoryInterface $versionRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(FetchVersionsCommand $command): void
    {
        $this->logger->info('Démarrage de la récupération des versions', [
            'force_update' => $command->isForceUpdate(),
            'request_id' => $command->getRequestId()
        ]);

        try {
            $versions = $this->versionService->fetchVersionsFromApi();

            if ($command->isForceUpdate()) {
                $this->versionRepository->clear();
                $this->versionRepository->saveAll($versions);
                $this->logger->info('Toutes les versions mises à jour (force)', [
                    'count' => count($versions)
                ]);
            } else {
                $existingVersionValues = $this->versionRepository
                    ->getExistingVersionValues(array_map(fn($v) => $v->getValue(), $versions));

                $newVersions = array_filter(
                    $versions,
                    fn($version) => !in_array($version->getValue(), $existingVersionValues, true)
                );

                if (!empty($newVersions)) {
                    $this->versionRepository->saveAll($newVersions);
                    $this->logger->info('Nouvelles versions ajoutées', [
                        'count' => count($newVersions),
                        'versions' => array_map(fn($v) => $v->getValue(), $newVersions)
                    ]);
                } else {
                    $this->logger->info('Aucune nouvelle version trouvée');
                }
            }

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des versions', [
                'error' => $e->getMessage(),
                'request_id' => $command->getRequestId()
            ]);
            throw $e;
        }
    }
}
