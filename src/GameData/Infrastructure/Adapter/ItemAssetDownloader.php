<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Adapter;

use App\GameData\Application\Port\ItemAssetDownloaderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Zeggriim\RiotApiDataDragon\DataDragon\Endpoint\ItemApiInterface;

final class ItemAssetDownloader implements ItemAssetDownloaderInterface
{
    private const CDN_BASE_URL = 'https://ddragon.leagueoflegends.com/cdn/%s/img/item/%s';

    public function __construct(
        private readonly ItemApiInterface $itemApi,
        private readonly HttpClientInterface $httpClient,
        #[Autowire('%app.items_images_dir%')]
        private readonly string $imagesDir,
    ) {
    }

    public function getImageFilenames(string $version): array
    {
        $collection = $this->itemApi->getItemsAsCollection($version);

        return array_map(static fn ($item) => $item->image->full, $collection->items);
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
