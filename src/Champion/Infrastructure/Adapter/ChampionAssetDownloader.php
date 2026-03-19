<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Adapter;

use App\Champion\Application\Port\ChampionAssetDownloaderInterface;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use App\SharedContext\Infrastructure\Asset\GameAssetDownloader;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class ChampionAssetDownloader implements ChampionAssetDownloaderInterface
{
    public function __construct(
        private readonly RiotChampionProviderInterface $championProvider,
        #[Autowire(service: 'app.asset_downloader.champion')]
        private readonly GameAssetDownloader $assetDownloader,
    ) {
    }

    public function getImageFilenames(string $version): array
    {
        $champions = $this->championProvider->fetchAllChampions($version);

        return array_map(static fn ($champion) => $champion->image->full, $champions);
    }

    public function downloadImage(string $version, string $filename): bool
    {
        return $this->assetDownloader->downloadImage($version, $filename);
    }

    public function downloadAll(string $version, array $filenames): \Generator
    {
        return $this->assetDownloader->downloadAll($version, $filenames);
    }
}
