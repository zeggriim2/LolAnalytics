<?php

declare(strict_types=1);

namespace App\Tests\Functional\Champion\Application\QueryHandler;

use App\Champion\Application\Query\GetChampionByRiotIdQuery;
use App\Champion\Application\ReadModel\ChampionDetailReadModel;
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

final class GetChampionByRiotIdHandlerTest extends KernelTestCase
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

    public function testGetChampionByRiotIdReturnsNullWhenNotFound(): void
    {
        // Given: empty database
        // When
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('NonExistent'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertNull($result);
    }

    public function testGetChampionByRiotIdReturnsDetailReadModel(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));

        // When
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('Aatrox'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertInstanceOf(ChampionDetailReadModel::class, $result);
        $this->assertSame('Aatrox', $result->riotId);
        $this->assertSame('15.1.1', $result->version);
        $this->assertSame('266', $result->championKey);
        $this->assertSame('Aatrox', $result->name);
        $this->assertSame('the Darkin Blade', $result->title);
        $this->assertSame('Once honored defenders...', $result->blurb);
        $this->assertSame('Blood Well', $result->partype);
        $this->assertSame(['Fighter', 'Tank'], $result->tags);
    }

    public function testGetChampionByRiotIdReturnsImageData(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));

        // When
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('Aatrox'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertNotNull($result);
        $this->assertSame('Aatrox.png', $result->image['full']);
        $this->assertSame('champion0.png', $result->image['sprite']);
        $this->assertSame('champion', $result->image['group']);
        $this->assertSame(0, $result->image['x']);
        $this->assertSame(0, $result->image['y']);
        $this->assertSame(48, $result->image['w']);
        $this->assertSame(48, $result->image['h']);
    }

    public function testGetChampionByRiotIdReturnsInfoAndStats(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));

        // When
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('Aatrox'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: info
        $this->assertNotNull($result);
        $this->assertSame(8, $result->info['attack']);
        $this->assertSame(4, $result->info['defense']);
        $this->assertSame(3, $result->info['magic']);
        $this->assertSame(4, $result->info['difficulty']);

        // Then: stats
        $this->assertSame(580.0, $result->stats['hp']);
        $this->assertSame(345.0, $result->stats['moveSpeed']);
        $this->assertSame(60.0, $result->stats['attackDamage']);
        $this->assertSame(0.651, $result->stats['attackSpeed']);
    }

    public function testGetChampionByRiotIdWithSpecificVersion(): void
    {
        // Given: same champion in 2 versions
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        VersionEntityFactory::createOne(['version' => '15.2.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Aatrox', '15.2.1'));

        // When: requesting specific version
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('Aatrox', '15.1.1'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertNotNull($result);
        $this->assertSame('Aatrox', $result->riotId);
        $this->assertSame('15.1.1', $result->version);
    }

    public function testGetChampionByRiotIdWithVersionReturnsNullWhenNotFound(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));

        // When: requesting non-existent version
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('Aatrox', '99.99.99'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertNull($result);
    }

    public function testGetChampionAmongMultipleChampions(): void
    {
        // Given: multiple champions
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Yasuo', '15.1.1'));
        $this->repository->save($this->createChampion('Zed', '15.1.1'));

        // When: requesting a specific one
        $envelope = $this->queryBus->dispatch(new GetChampionByRiotIdQuery('Yasuo'));
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then
        $this->assertNotNull($result);
        $this->assertSame('Yasuo', $result->riotId);
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
            image: new ChampionImage($riotId . '.png', 'champion0.png', 'champion', 0, 0, 48, 48),
            info: new ChampionInfo(8, 4, 3, 4),
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
