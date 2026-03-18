<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Presentation\Console;

use App\Champion\Application\Port\ChampionAssetDownloaderInterface;
use App\Champion\Presentation\Console\DownloadChampionImagesConsoleCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class DownloadChampionImagesConsoleCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new DownloadChampionImagesConsoleCommand($this->createStub(ChampionAssetDownloaderInterface::class))
        );
    }

    public function testExecuteDownloadsAllNewImages(): void
    {
        $downloader = $this->createStub(ChampionAssetDownloaderInterface::class);
        $downloader->method('getImageFilenames')->willReturn(['Aatrox.png', 'Yasuo.png']);
        $downloader->method('downloadAll')->willReturn($this->generatorFrom(['Aatrox.png' => true, 'Yasuo.png' => true]));

        $exitCode = $this->runCommand($downloader, '15.1.1');

        self::assertSame(Command::SUCCESS, $exitCode);
        self::assertStringContainsString('2 image(s) downloaded', $this->normalizedDisplay());
        self::assertStringContainsString('(0 already present)', $this->normalizedDisplay());
    }

    public function testExecuteReportsAlreadyPresentImages(): void
    {
        $downloader = $this->createStub(ChampionAssetDownloaderInterface::class);
        $downloader->method('getImageFilenames')->willReturn(['Aatrox.png', 'Yasuo.png', 'Jinx.png']);
        $downloader->method('downloadAll')->willReturn($this->generatorFrom([
            'Aatrox.png' => false,
            'Yasuo.png' => true,
            'Jinx.png' => false,
        ]));

        $exitCode = $this->runCommand($downloader, '15.1.1');

        self::assertSame(Command::SUCCESS, $exitCode);
        self::assertStringContainsString('1 image(s) downloaded', $this->normalizedDisplay());
        self::assertStringContainsString('(2 already present)', $this->normalizedDisplay());
    }

    public function testExecuteWithNoChampions(): void
    {
        $downloader = $this->createStub(ChampionAssetDownloaderInterface::class);
        $downloader->method('getImageFilenames')->willReturn([]);
        $downloader->method('downloadAll')->willReturn($this->generatorFrom([]));

        $exitCode = $this->runCommand($downloader, '15.1.1');

        self::assertSame(Command::SUCCESS, $exitCode);
        self::assertStringContainsString('0 image(s) downloaded', $this->normalizedDisplay());
    }

    public function testExecuteReturnsFailureOnException(): void
    {
        $downloader = $this->createStub(ChampionAssetDownloaderInterface::class);
        $downloader->method('getImageFilenames')->willThrowException(new \RuntimeException('API unreachable'));

        $exitCode = $this->runCommand($downloader, '15.1.1');

        self::assertSame(Command::FAILURE, $exitCode);
        self::assertStringContainsString('API unreachable', $this->commandTester->getDisplay());
    }

    public function testExecutePassesVersionToDownloader(): void
    {
        $downloader = $this->createMock(ChampionAssetDownloaderInterface::class);
        $downloader->expects($this->once())->method('getImageFilenames')->with('14.24.1')->willReturn([]);
        $downloader->method('downloadAll')->willReturn($this->generatorFrom([]));

        $this->runCommand($downloader, '14.24.1');
    }

    public function testOutputContainsVersionInSuccessMessage(): void
    {
        $downloader = $this->createStub(ChampionAssetDownloaderInterface::class);
        $downloader->method('getImageFilenames')->willReturn(['Aatrox.png']);
        $downloader->method('downloadAll')->willReturn($this->generatorFrom(['Aatrox.png' => true]));

        $this->runCommand($downloader, '15.1.1');

        self::assertStringContainsString('15.1.1', $this->commandTester->getDisplay());
    }

    private function runCommand(ChampionAssetDownloaderInterface $downloader, string $version): int
    {
        $this->commandTester = new CommandTester(new DownloadChampionImagesConsoleCommand($downloader));

        return $this->commandTester->execute(['version' => $version]);
    }

    private function normalizedDisplay(): string
    {
        return preg_replace('/\s+/', ' ', $this->commandTester->getDisplay()) ?? '';
    }

    /**
     * @param array<string, bool> $items
     */
    private function generatorFrom(array $items): \Generator
    {
        yield from $items;
    }
}
