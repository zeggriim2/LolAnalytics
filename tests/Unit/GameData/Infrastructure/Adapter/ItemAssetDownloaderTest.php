<?php

declare(strict_types=1);

namespace App\Tests\Unit\GameData\Infrastructure\Adapter;

use App\GameData\Infrastructure\Adapter\ItemAssetDownloader;
use App\SharedContext\Infrastructure\Asset\GameAssetDownloader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Image;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Item\Gold;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Item\Item;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Item\ItemCollection;
use Zeggriim\RiotApiDataDragon\DataDragon\Endpoint\ItemApiInterface;

final class ItemAssetDownloaderTest extends TestCase
{
    private string $imagesDir;

    protected function setUp(): void
    {
        $this->imagesDir = sys_get_temp_dir() . '/lol_test_items_' . uniqid();
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->imagesDir);
    }

    public function testGetImageFilenamesReturnsItemImageFullNames(): void
    {
        $itemApi = $this->createMock(ItemApiInterface::class);
        $itemApi
            ->expects($this->once())
            ->method('getItemsAsCollection')
            ->with('15.1.1')
            ->willReturn($this->createItemCollection([
                $this->createItem('1001.png'),
                $this->createItem('3006.png'),
            ]));

        $downloader = $this->buildDownloader(itemApi: $itemApi);

        self::assertSame(['1001.png', '3006.png'], $downloader->getImageFilenames('15.1.1'));
    }

    public function testGetImageFilenamesReturnsEmptyArrayWhenNoItems(): void
    {
        $itemApi = $this->createStub(ItemApiInterface::class);
        $itemApi->method('getItemsAsCollection')->willReturn($this->createItemCollection([]));

        $downloader = $this->buildDownloader(itemApi: $itemApi);

        self::assertSame([], $downloader->getImageFilenames('15.1.1'));
    }

    public function testDownloadImageCreatesFileAndReturnsTrue(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('fake_image_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(Request::METHOD_GET, 'https://ddragon.leagueoflegends.com/cdn/15.1.1/img/item/1001.png')
            ->willReturn($response);

        $result = $this->buildDownloader(httpClient: $httpClient)->downloadImage('15.1.1', '1001.png');

        self::assertTrue($result);
        self::assertFileExists($this->imagesDir . '/15.1.1/1001.png');
        self::assertSame('fake_image_data', file_get_contents($this->imagesDir . '/15.1.1/1001.png'));
    }

    public function testDownloadImageReturnsFalseWhenFileAlreadyExists(): void
    {
        $targetDir = $this->imagesDir . '/15.1.1';
        mkdir($targetDir, 0755, true);
        file_put_contents($targetDir . '/1001.png', 'existing_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->never())->method('request');

        $result = $this->buildDownloader(httpClient: $httpClient)->downloadImage('15.1.1', '1001.png');

        self::assertFalse($result);
    }

    public function testDownloadImageCreatesVersionDirectory(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('data');

        $httpClient = $this->createStub(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        self::assertDirectoryDoesNotExist($this->imagesDir . '/15.1.1');

        $this->buildDownloader(httpClient: $httpClient)->downloadImage('15.1.1', '1001.png');

        self::assertDirectoryExists($this->imagesDir . '/15.1.1');
    }

    public function testDownloadAllYieldsFalseForAlreadyPresentFiles(): void
    {
        $targetDir = $this->imagesDir . '/15.1.1';
        mkdir($targetDir, 0755, true);
        file_put_contents($targetDir . '/1001.png', 'existing');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->never())->method('request');

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['1001.png'])
        );

        self::assertSame(['1001.png' => false], $results);
    }

    public function testDownloadAllDownloadsNewFilesAndYieldsTrue(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('image_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(Request::METHOD_GET, 'https://ddragon.leagueoflegends.com/cdn/15.1.1/img/item/3006.png')
            ->willReturn($response);

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['3006.png'])
        );

        self::assertSame(['3006.png' => true], $results);
        self::assertFileExists($this->imagesDir . '/15.1.1/3006.png');
    }

    public function testDownloadAllFiresAllRequestsConcurrently(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->exactly(3))
            ->method('request')
            ->willReturn($response);

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['1001.png', '3006.png', '3157.png'])
        );

        self::assertSame(['1001.png' => true, '3006.png' => true, '3157.png' => true], $results);
    }

    public function testDownloadAllMixesCachedAndNewFiles(): void
    {
        $targetDir = $this->imagesDir . '/15.1.1';
        mkdir($targetDir, 0755, true);
        file_put_contents($targetDir . '/1001.png', 'existing');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('new_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(Request::METHOD_GET, 'https://ddragon.leagueoflegends.com/cdn/15.1.1/img/item/3006.png')
            ->willReturn($response);

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['1001.png', '3006.png'])
        );

        self::assertFalse($results['1001.png']);
        self::assertTrue($results['3006.png']);
    }

    private function buildDownloader(
        ?ItemApiInterface $itemApi = null,
        ?HttpClientInterface $httpClient = null,
    ): ItemAssetDownloader {
        $assetDownloader = new GameAssetDownloader(
            $httpClient ?? $this->createStub(HttpClientInterface::class),
            'https://ddragon.leagueoflegends.com/cdn/%s/img/item/%s',
            $this->imagesDir,
        );

        return new ItemAssetDownloader(
            $itemApi ?? $this->createStub(ItemApiInterface::class),
            $assetDownloader,
        );
    }

    private function createItem(string $imageFilename): Item
    {
        return new Item(
            name: 'Item',
            description: 'Description',
            colloq: ';',
            image: new Image(
                full: $imageFilename,
                sprite: 'item0.png',
                group: 'item',
                x: 0,
                y: 0,
                w: 48,
                h: 48,
            ),
            gold: new Gold(base: 0, purchasable: true, sell: 0, total: 0),
            tags: [],
            maps: [],
            stats: [],
        );
    }

    private function createItemCollection(array $items): ItemCollection
    {
        return new ItemCollection(
            type: 'item',
            version: '15.1.1',
            basic: [],
            items: $items,
            groups: [],
            tree: [],
        );
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }

            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}
