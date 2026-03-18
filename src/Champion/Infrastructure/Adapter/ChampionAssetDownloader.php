<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Adapter;

use App\Champion\Application\Port\ChampionAssetDownloaderInterface;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ChampionAssetDownloader implements ChampionAssetDownloaderInterface
{
    private const CDN_BASE_URL = 'https://ddragon.leagueoflegends.com/cdn/%s/img/champion/%s';

    public function __construct(
        private readonly RiotChampionProviderInterface $championProvider,
        private readonly HttpClientInterface $httpClient,
        #[Autowire('%app.champions_images_dir%')]
        private readonly string $imagesDir,
    ) {
    }

    public function getImageFilenames(string $version): array
    {
        $champions = $this->championProvider->fetchAllChampions($version);

        return array_map(static fn ($champion) => $champion->image->full, $champions);
    }

    public function downloadImage(string $version, string $filename): bool
    {
        $targetDir = sprintf('%s/%s', $this->imagesDir, $version);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $filename;

        if (file_exists($targetPath)) {
            return false;
        }

        $response = $this->httpClient->request('GET', sprintf(self::CDN_BASE_URL, $version, $filename));
        file_put_contents($targetPath, $response->getContent());

        return true;
    }

    public function downloadAll(string $version, array $filenames): \Generator
    {
        $targetDir = sprintf('%s/%s', $this->imagesDir, $version);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $pending = [];

        foreach ($filenames as $filename) {
            if (file_exists($targetDir . '/' . $filename)) {
                yield $filename => false;
                continue;
            }

            $pending[$filename] = $this->httpClient->request(
                'GET',
                sprintf(self::CDN_BASE_URL, $version, $filename),
            );
        }

        foreach ($pending as $filename => $response) {
            file_put_contents($targetDir . '/' . $filename, $response->getContent());
            yield $filename => true;
        }
    }
}
