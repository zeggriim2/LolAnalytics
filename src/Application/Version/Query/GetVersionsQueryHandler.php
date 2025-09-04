<?php

declare(strict_types=1);

namespace App\Application\Version\Query;

use App\Domain\Version\Model\Version;
use App\Domain\Version\Repository\VersionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class GetVersionsQueryHandler
{
    public function __construct(
        private readonly VersionRepositoryInterface $versionRepository,
    ) {}

    /**
     * @return Version[]
     */
    public function __invoke(GetVersionsQuery $query): array
    {
        if ($query->isOnlyActive()) {
            $activeVersion = $this->versionRepository->findActive();
            return $activeVersion ? [$activeVersion] : [];
        }

        return $this->versionRepository->findAll();
    }
}
