<?php

declare(strict_types=1);

namespace App\Tests\Functional\Champion\Application\CommandHandler;

use App\Champion\Application\Command\SyncChampionsCommand;
use App\Champion\Application\Dto\ChampionDto;
use App\Champion\Application\Dto\ChampionImageDto;
use App\Champion\Application\Dto\ChampionInfoDto;
use App\Champion\Application\Dto\ChampionStatsDto;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use App\Tests\Factory\VersionEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

final class SyncChampionsHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $commandBus;
    private ChampionRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->commandBus = $container->get('command.bus');
        $this->repository = $container->get(ChampionRepositoryInterface::class);
    }

    public function testSyncChampionsPersistsToDatabase(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);

        $dto = $this->createChampionDto('Aatrox', '266', '15.1.1');

        $mockProvider = $this->createMock(RiotChampionProviderInterface::class);
        $mockProvider
            ->expects($this->once())
            ->method('fetchAllChampions')
            ->with('15.1.1', 'fr_FR')
            ->willReturn([$dto]);

        static::getContainer()->set(RiotChampionProviderInterface::class, $mockProvider);

        // When
        $this->commandBus->dispatch(new SyncChampionsCommand('15.1.1', 'fr_FR'));

        // Then
        $champion = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($champion);
        $this->assertSame('Aatrox', $champion->riotId());
        $this->assertSame('15.1.1', $champion->version());
        $this->assertSame('266', $champion->championKey());
        $this->assertSame('Aatrox', $champion->name());
    }

    public function testSyncChampionsPersistsImageData(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);

        $dto = $this->createChampionDto('Aatrox', '266', '15.1.1');

        $mockProvider = $this->createStub(RiotChampionProviderInterface::class);
        $mockProvider
            ->method('fetchAllChampions')
            ->willReturn([$dto]);

        static::getContainer()->set(RiotChampionProviderInterface::class, $mockProvider);

        // When
        $this->commandBus->dispatch(new SyncChampionsCommand('15.1.1'));

        // Then
        $champion = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($champion);
        $image = $champion->image();
        $this->assertSame('Aatrox.png', $image->full());
        $this->assertSame('champion0.png', $image->sprite());
        $this->assertSame('champion', $image->group());
        $this->assertSame(0, $image->x());
        $this->assertSame(0, $image->y());
        $this->assertSame(48, $image->w());
        $this->assertSame(48, $image->h());
    }

    public function testSyncChampionsPersistsInfoAndStatsData(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);

        $dto = $this->createChampionDto('Aatrox', '266', '15.1.1');

        $mockProvider = $this->createStub(RiotChampionProviderInterface::class);
        $mockProvider
            ->method('fetchAllChampions')
            ->willReturn([$dto]);

        static::getContainer()->set(RiotChampionProviderInterface::class, $mockProvider);

        // When
        $this->commandBus->dispatch(new SyncChampionsCommand('15.1.1'));

        // Then
        $champion = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($champion);
        $this->assertSame(8, $champion->info()->attack());
        $this->assertSame(4, $champion->info()->defense());
        $this->assertSame(580.0, $champion->stats()->hp());
        $this->assertSame(345.0, $champion->stats()->moveSpeed());
    }

    public function testSyncMultipleChampionsPersistsAll(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);

        $dtos = [
            $this->createChampionDto('Aatrox', '266', '15.1.1'),
            $this->createChampionDto('Yasuo', '157', '15.1.1'),
            $this->createChampionDto('Zed', '238', '15.1.1'),
        ];

        $mockProvider = $this->createStub(RiotChampionProviderInterface::class);
        $mockProvider
            ->method('fetchAllChampions')
            ->willReturn($dtos);

        static::getContainer()->set(RiotChampionProviderInterface::class, $mockProvider);

        // When
        $this->commandBus->dispatch(new SyncChampionsCommand('15.1.1'));

        // Then
        $champions = $this->repository->findByVersion('15.1.1');
        $this->assertCount(3, $champions);
    }

    public function testSyncChampionsIsIdempotent(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);

        $dto = $this->createChampionDto('Aatrox', '266', '15.1.1');

        $mockProvider = $this->createStub(RiotChampionProviderInterface::class);
        $mockProvider
            ->method('fetchAllChampions')
            ->willReturn([$dto]);

        static::getContainer()->set(RiotChampionProviderInterface::class, $mockProvider);

        // When: dispatching twice
        $this->commandBus->dispatch(new SyncChampionsCommand('15.1.1'));
        $this->commandBus->dispatch(new SyncChampionsCommand('15.1.1'));

        // Then: should still have only one champion
        $champions = $this->repository->findByVersion('15.1.1');
        $this->assertCount(1, $champions);
    }

    public function testSyncChampionsDoesNothingWhenVersionNotInDatabase(): void
    {
        // Given: no VersionEntity for '99.99.99'
        $dto = $this->createChampionDto('Aatrox', '266', '99.99.99');

        $mockProvider = $this->createStub(RiotChampionProviderInterface::class);
        $mockProvider
            ->method('fetchAllChampions')
            ->willReturn([$dto]);

        static::getContainer()->set(RiotChampionProviderInterface::class, $mockProvider);

        // When
        $this->commandBus->dispatch(new SyncChampionsCommand('99.99.99'));

        // Then: nothing persisted
        $champions = $this->repository->findAll();
        $this->assertCount(0, $champions);
    }

    private function createChampionDto(string $riotId, string $key, string $version): ChampionDto
    {
        return new ChampionDto(
            riotId: $riotId,
            version: $version,
            championKey: $key,
            name: $riotId,
            title: 'the Darkin Blade',
            blurb: 'Once honored defenders...',
            partype: 'Blood Well',
            tags: ['Fighter', 'Tank'],
            image: new ChampionImageDto(
                full: $riotId . '.png',
                sprite: 'champion0.png',
                group: 'champion',
                x: 0,
                y: 0,
                w: 48,
                h: 48,
            ),
            info: new ChampionInfoDto(
                attack: 8,
                defense: 4,
                magic: 3,
                difficulty: 4,
            ),
            stats: new ChampionStatsDto(
                hp: 580.0,
                hpPerLevel: 90.0,
                mp: 0.0,
                mpPerLevel: 0.0,
                moveSpeed: 345.0,
                armor: 38.0,
                armorPerLevel: 3.25,
                spellBlock: 32.0,
                spellBlockPerLevel: 1.25,
                attackRange: 175.0,
                hpRegen: 3.0,
                hpRegenPerLevel: 1.0,
                mpRegen: 0.0,
                mpRegenPerLevel: 0.0,
                crit: 0.0,
                critPerLevel: 0.0,
                attackDamage: 60.0,
                attackDamagePerLevel: 5.0,
                attackSpeed: 0.651,
                attackSpeedPerLevel: 2.5,
            ),
        );
    }
}
