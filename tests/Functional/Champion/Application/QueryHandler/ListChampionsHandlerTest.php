<?php

declare(strict_types=1);

namespace App\Tests\Functional\Champion\Application\QueryHandler;

use App\Champion\Application\Query\ListChampionsQuery;
use App\Champion\Application\ReadModel\ChampionReadModel;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use App\Tests\Factory\VersionEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ListChampionsHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $queryBus;
    private ChampionRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->queryBus = $container->get('query.bus');
        $this->repository = $container->get(ChampionRepositoryInterface::class);
    }

    public function testListChampionsWithEmptyDatabase(): void
    {
        // Given: empty database
        // When
        $envelope = $this->queryBus->dispatch(new ListChampionsQuery());
        $champions = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertIsArray($champions);
        $this->assertCount(0, $champions);
    }

    public function testListChampionsReturnsAllChampions(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Yasuo', '15.1.1'));

        // When
        $envelope = $this->queryBus->dispatch(new ListChampionsQuery());
        $champions = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertIsArray($champions);
        $this->assertCount(2, $champions);
        $this->assertContainsOnlyInstancesOf(ChampionReadModel::class, $champions);
    }

    public function testListChampionsByVersion(): void
    {
        // Given: champions in 2 different versions
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        VersionEntityFactory::createOne(['version' => '15.2.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Yasuo', '15.2.1'));

        // When: filtering by version 15.1.1
        $envelope = $this->queryBus->dispatch(new ListChampionsQuery('15.1.1'));
        $champions = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only champions from 15.1.1
        $this->assertCount(1, $champions);
        $this->assertSame('Aatrox', $champions[0]->riotId);
        $this->assertSame('15.1.1', $champions[0]->version);
    }

    public function testListChampionsReturnsCorrectReadModel(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));

        // When
        $envelope = $this->queryBus->dispatch(new ListChampionsQuery());
        $champions = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertCount(1, $champions);
        $champion = $champions[0];
        $this->assertSame('Aatrox', $champion->riotId);
        $this->assertSame('15.1.1', $champion->version);
        $this->assertSame('266', $champion->championKey);
        $this->assertSame('Aatrox', $champion->name);
        $this->assertSame('the Darkin Blade', $champion->title);
        $this->assertSame('Blood Well', $champion->partype);
        $this->assertSame(['Fighter', 'Tank'], $champion->tags);
        $this->assertSame('Aatrox.png', $champion->imageFull);
    }

    private function createChampion(string $riotId, string $version): Champion
    {
        return Champion::create(
            riotId: $riotId,
            version: $version,
            championKey: '266',
            name: $riotId,
            title: 'the Darkin Blade',
            blurb: 'Once honored defenders...',
            partype: 'Blood Well',
            tags: ['Fighter', 'Tank'],
            image: new ChampionImage(
                $riotId . '.png',
                'champion0.png',
                'champion',
                0,
                0,
                48,
                48
            ),
            info: new ChampionInfo(
                8,
                4,
                3,
                4
            ),
            stats: new ChampionStats(
                580.0,
                90.0,
                0.0,
                0.0,
                345.0,
                38.0,
                3.25,
                32.0,
                1.25,
                175.0,
                3.0,
                1.0,
                0.0,
                0.0,
                0.0,
                0.0,
                60.0,
                5.0,
                0.651,
                2.5,
            ),
        );
    }
}
