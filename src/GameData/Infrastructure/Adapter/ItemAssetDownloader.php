<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Adapter;

use App\GameData\Application\Port\ItemAssetDownloaderInterface;
use App\SharedContext\Infrastructure\Asset\GameAssetDownloader;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Zeggriim\RiotApiDataDragon\DataDragon\Endpoint\ItemApiInterface;

final class ItemAssetDownloader implements ItemAssetDownloaderInterface
{
    public function __construct(
        private readonly ItemApiInterface $itemApi,
        #[Autowire(service: 'app.asset_downloader.item')]
        private readonly GameAssetDownloader $assetDownloader,
    ) {
    }

    public function getImageFilenames(string $version): array
    {
        $collection = $this->itemApi->getItemsAsCollection($version);

        return array_map(static fn ($item) => $item->image->full, $collection->items);
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
