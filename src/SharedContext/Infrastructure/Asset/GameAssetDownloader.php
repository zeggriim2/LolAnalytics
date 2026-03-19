<?php

declare(strict_types=1);

namespace App\SharedContext\Infrastructure\Asset;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GameAssetDownloader
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $cdnBaseUrl,
        private readonly string $imagesDir,
    ) {
    }

    /**
     * Downloads a single asset image.
     * Returns true if downloaded, false if already present.
     */
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

        $response = $this->httpClient->request(Request::METHOD_GET, sprintf($this->cdnBaseUrl, $version, $filename));
        file_put_contents($targetPath, $response->getContent());

        return true;
    }

    /**
     * Downloads all asset images concurrently.
     * Yields filename => wasDownloaded (bool) as each response completes.
     *
     * @param string[] $filenames
     *
     * @return \Generator<string, bool>
     */
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
                Request::METHOD_GET,
                sprintf($this->cdnBaseUrl, $version, $filename),
            );
        }

        foreach ($pending as $filename => $response) {
            file_put_contents($targetDir . '/' . $filename, $response->getContent());
            yield $filename => true;
        }
    }
}
