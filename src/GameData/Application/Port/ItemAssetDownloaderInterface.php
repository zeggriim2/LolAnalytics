<?php

declare(strict_types=1);

namespace App\GameData\Application\Port;

interface ItemAssetDownloaderInterface
{
    /**
     * Returns the list of image filenames for the given version.
     *
     * @return string[]
     */
    public function getImageFilenames(string $version): array;

    /**
     * Downloads a single item image.
     * Returns true if downloaded, false if already present.
     */
    public function downloadImage(string $version, string $filename): bool;

    /**
     * Downloads all item images concurrently.
     * Yields filename => wasDownloaded (bool) as each response completes.
     *
     * @param string[] $filenames
     *
     * @return \Generator<string, bool>
     */
    public function downloadAll(string $version, array $filenames): \Generator;
}
