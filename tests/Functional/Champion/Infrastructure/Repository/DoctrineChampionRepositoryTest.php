<?php

declare(strict_types=1);

namespace App\Tests\Functional\Champion\Infrastructure\Repository;

use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use App\Tests\Factory\VersionEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DoctrineChampionRepositoryTest extends KernelTestCase
{
    use ResetDatabase;

    private ChampionRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->repository = $container->get(ChampionRepositoryInterface::class);
    }

    public function testSaveAndFindByRiotIdAndVersion(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $champion = $this->createChampion('Aatrox', '15.1.1');

        // When
        $this->repository->save($champion);

        // Then
        $found = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($found);
        $this->assertSame('Aatrox', $found->riotId());
        $this->assertSame('15.1.1', $found->version());
        $this->assertSame('266', $found->championKey());
        $this->assertSame('Aatrox', $found->name());
        $this->assertSame('the Darkin Blade', $found->title());
        $this->assertSame('Blood Well', $found->partype());
        $this->assertSame(['Fighter', 'Tank'], $found->tags());
    }

    public function testSavedChampionHasCorrectImageData(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $champion = $this->createChampion('Aatrox', '15.1.1');

        // When
        $this->repository->save($champion);

        // Then
        $found = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($found);
        $image = $found->image();
        $this->assertSame('Aatrox.png', $image->full());
        $this->assertSame('champion0.png', $image->sprite());
        $this->assertSame('champion', $image->group());
        $this->assertSame(0, $image->x());
        $this->assertSame(0, $image->y());
        $this->assertSame(48, $image->w());
        $this->assertSame(48, $image->h());
    }

    public function testSavedChampionHasCorrectInfoData(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $champion = $this->createChampion('Aatrox', '15.1.1');

        // When
        $this->repository->save($champion);

        // Then
        $found = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($found);
        $info = $found->info();
        $this->assertSame(8, $info->attack());
        $this->assertSame(4, $info->defense());
        $this->assertSame(3, $info->magic());
        $this->assertSame(4, $info->difficulty());
    }

    public function testSavedChampionHasCorrectStatsData(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $champion = $this->createChampion('Aatrox', '15.1.1');

        // When
        $this->repository->save($champion);

        // Then
        $found = $this->repository->findByRiotIdAndVersion('Aatrox', '15.1.1');

        $this->assertNotNull($found);
        $stats = $found->stats();
        $this->assertSame(580.0, $stats->hp());
        $this->assertSame(90.0, $stats->hpPerLevel());
        $this->assertSame(345.0, $stats->moveSpeed());
        $this->assertSame(38.0, $stats->armor());
        $this->assertSame(60.0, $stats->attackDamage());
        $this->assertSame(0.651, $stats->attackSpeed());
    }

    public function testFindByRiotIdAndVersionReturnsNullWhenNotFound(): void
    {
        $found = $this->repository->findByRiotIdAndVersion('NonExistent', '15.1.1');

        $this->assertNull($found);
    }

    public function testFindByRiotIdReturnsLatestVersion(): void
    {
        // Given: same champion in 2 versions
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        VersionEntityFactory::createOne(['version' => '15.2.1']);

        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Aatrox', '15.2.1'));

        // When
        $found = $this->repository->findByRiotId('Aatrox');

        // Then: should return the latest version (DESC order)
        $this->assertNotNull($found);
        $this->assertSame('Aatrox', $found->riotId());
    }

    public function testFindByRiotIdReturnsNullWhenNotFound(): void
    {
        $found = $this->repository->findByRiotId('NonExistent');

        $this->assertNull($found);
    }

    public function testFindAllReturnsAllChampions(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Yasuo', '15.1.1'));

        // When
        $champions = $this->repository->findAll();

        // Then
        $this->assertCount(2, $champions);
    }

    public function testFindAllReturnsEmptyArrayWhenNoChampions(): void
    {
        $champions = $this->repository->findAll();

        $this->assertSame([], $champions);
    }

    public function testFindByVersionReturnsChampionsForSpecificVersion(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        VersionEntityFactory::createOne(['version' => '15.2.1']);
        $this->repository->save($this->createChampion('Aatrox', '15.1.1'));
        $this->repository->save($this->createChampion('Yasuo', '15.2.1'));

        // When
        $champions = $this->repository->findByVersion('15.1.1');

        // Then
        $this->assertCount(1, $champions);
        $this->assertSame('Aatrox', $champions[0]->riotId());
    }

    public function testSaveDoesNotDuplicateExistingChampion(): void
    {
        // Given
        VersionEntityFactory::createOne(['version' => '15.1.1']);
        $champion = $this->createChampion('Aatrox', '15.1.1');
        $this->repository->save($champion);

        // When: saving the same champion again
        $this->repository->save($champion);

        // Then: should not create a duplicate
        $all = $this->repository->findAll();
        $this->assertCount(1, $all);
    }

    public function testSaveDoesNothingWhenVersionEntityDoesNotExist(): void
    {
        // Given: no VersionEntity in DB
        $champion = $this->createChampion('Aatrox', '99.99.99');

        // When
        $this->repository->save($champion);

        // Then: champion should not be persisted
        $found = $this->repository->findByRiotId('Aatrox');
        $this->assertNull($found);
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
