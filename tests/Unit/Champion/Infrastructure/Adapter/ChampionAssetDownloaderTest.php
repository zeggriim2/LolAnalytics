<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Infrastructure\Adapter;

use App\Champion\Application\Dto\ChampionDto;
use App\Champion\Application\Dto\ChampionImageDto;
use App\Champion\Application\Dto\ChampionInfoDto;
use App\Champion\Application\Dto\ChampionStatsDto;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use App\Champion\Infrastructure\Adapter\ChampionAssetDownloader;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ChampionAssetDownloaderTest extends TestCase
{
    private string $imagesDir;

    protected function setUp(): void
    {
        $this->imagesDir = sys_get_temp_dir() . '/lol_test_champions_' . uniqid();
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->imagesDir);
    }

    public function testGetImageFilenamesReturnsChampionImageFullNames(): void
    {
        $provider = $this->createMock(RiotChampionProviderInterface::class);
        $provider
            ->expects($this->once())
            ->method('fetchAllChampions')
            ->with('15.1.1')
            ->willReturn([
                $this->createChampionDto('Aatrox', 'Aatrox.png'),
                $this->createChampionDto('Yasuo', 'Yasuo.png'),
            ]);

        $downloader = $this->buildDownloader(provider: $provider);

        self::assertSame(['Aatrox.png', 'Yasuo.png'], $downloader->getImageFilenames('15.1.1'));
    }

    public function testGetImageFilenamesReturnsEmptyArrayWhenNoChampions(): void
    {
        $provider = $this->createStub(RiotChampionProviderInterface::class);
        $provider->method('fetchAllChampions')->willReturn([]);

        $downloader = $this->buildDownloader(provider: $provider);

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
            ->with('GET', 'https://ddragon.leagueoflegends.com/cdn/15.1.1/img/champion/Aatrox.png')
            ->willReturn($response);

        $downloader = $this->buildDownloader(httpClient: $httpClient);

        $result = $downloader->downloadImage('15.1.1', 'Aatrox.png');

        self::assertTrue($result);
        self::assertFileExists($this->imagesDir . '/15.1.1/Aatrox.png');
        self::assertSame('fake_image_data', file_get_contents($this->imagesDir . '/15.1.1/Aatrox.png'));
    }

    public function testDownloadImageReturnsFalseWhenFileAlreadyExists(): void
    {
        $targetDir = $this->imagesDir . '/15.1.1';
        mkdir($targetDir, 0755, true);
        file_put_contents($targetDir . '/Aatrox.png', 'existing_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->never())->method('request');

        $result = $this->buildDownloader(httpClient: $httpClient)->downloadImage('15.1.1', 'Aatrox.png');

        self::assertFalse($result);
    }

    public function testDownloadImageCreatesVersionDirectory(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('data');

        $httpClient = $this->createStub(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        self::assertDirectoryDoesNotExist($this->imagesDir . '/15.1.1');

        $this->buildDownloader(httpClient: $httpClient)->downloadImage('15.1.1', 'Aatrox.png');

        self::assertDirectoryExists($this->imagesDir . '/15.1.1');
    }

    public function testDownloadAllYieldsFalseForAlreadyPresentFiles(): void
    {
        $targetDir = $this->imagesDir . '/15.1.1';
        mkdir($targetDir, 0755, true);
        file_put_contents($targetDir . '/Aatrox.png', 'existing');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->never())->method('request');

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['Aatrox.png'])
        );

        self::assertSame(['Aatrox.png' => false], $results);
    }

    public function testDownloadAllDownloadsNewFilesAndYieldsTrue(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('image_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://ddragon.leagueoflegends.com/cdn/15.1.1/img/champion/Aatrox.png')
            ->willReturn($response);

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['Aatrox.png'])
        );

        self::assertSame(['Aatrox.png' => true], $results);
        self::assertFileExists($this->imagesDir . '/15.1.1/Aatrox.png');
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
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['Aatrox.png', 'Yasuo.png', 'Jinx.png'])
        );

        self::assertSame(['Aatrox.png' => true, 'Yasuo.png' => true, 'Jinx.png' => true], $results);
    }

    public function testDownloadAllMixesCachedAndNewFiles(): void
    {
        $targetDir = $this->imagesDir . '/15.1.1';
        mkdir($targetDir, 0755, true);
        file_put_contents($targetDir . '/Aatrox.png', 'existing');

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')->willReturn('new_data');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://ddragon.leagueoflegends.com/cdn/15.1.1/img/champion/Yasuo.png')
            ->willReturn($response);

        $results = iterator_to_array(
            $this->buildDownloader(httpClient: $httpClient)->downloadAll('15.1.1', ['Aatrox.png', 'Yasuo.png'])
        );

        self::assertFalse($results['Aatrox.png']);
        self::assertTrue($results['Yasuo.png']);
    }

    private function buildDownloader(
        ?RiotChampionProviderInterface $provider = null,
        ?HttpClientInterface $httpClient = null,
    ): ChampionAssetDownloader {
        return new ChampionAssetDownloader(
            $provider ?? $this->createStub(RiotChampionProviderInterface::class),
            $httpClient ?? $this->createStub(HttpClientInterface::class),
            $this->imagesDir,
        );
    }

    private function createChampionDto(string $riotId, string $imageFilename): ChampionDto
    {
        return new ChampionDto(
            riotId: $riotId,
            version: '15.1.1',
            championKey: '1',
            name: $riotId,
            title: 'Title',
            blurb: 'Blurb',
            partype: 'Mana',
            tags: ['Fighter'],
            image: new ChampionImageDto(
                full: $imageFilename,
                sprite: 'champion0.png',
                group: 'champion',
                x: 0,
                y: 0,
                w: 48,
                h: 48,
            ),
            info: new ChampionInfoDto(attack: 5, defense: 5, magic: 5, difficulty: 5),
            stats: new ChampionStatsDto(
                hp: 580.0,
                hpPerLevel: 90.0,
                mp: 350.0,
                mpPerLevel: 32.0,
                moveSpeed: 345.0,
                armor: 38.0,
                armorPerLevel: 3.25,
                spellBlock: 32.0,
                spellBlockPerLevel: 1.25,
                attackRange: 175.0,
                hpRegen: 3.0,
                hpRegenPerLevel: 1.0,
                mpRegen: 8.0,
                mpRegenPerLevel: 0.8,
                crit: 0.0,
                critPerLevel: 0.0,
                attackDamage: 60.0,
                attackDamagePerLevel: 5.0,
                attackSpeed: 0.651,
                attackSpeedPerLevel: 2.5,
            ),
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
