<?php

declare(strict_types=1);

namespace App\Application\Version\Query;

use App\Domain\Version\Model\Version;
use App\Domain\Version\Repository\VersionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class GetVersionQueryHandler
{
    public function __construct(
        private readonly VersionRepositoryInterface $versionRepository,
    ) {}

    /**
     * @return Version[]
     */
    public function __invoke(GetVersionQuery $query): array
    {
       return $this->versionRepository->findAll();
    }
}
