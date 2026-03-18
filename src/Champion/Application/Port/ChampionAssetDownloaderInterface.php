<?php

declare(strict_types=1);

namespace App\Champion\Application\Port;

interface ChampionAssetDownloaderInterface
{
    /**
     * Returns the list of image filenames for the given version.
     *
     * @return string[]
     */
    public function getImageFilenames(string $version): array;

    /**
     * Downloads a single champion image.
     * Returns true if downloaded, false if already present.
     */
    public function downloadImage(string $version, string $filename): bool;

    /**
     * Downloads all champion images concurrently.
     * Yields filename => wasDownloaded (bool) as each response completes.
     *
     * @param string[] $filenames
     *
     * @return \Generator<string, bool>
     */
    public function downloadAll(string $version, array $filenames): \Generator;
}
