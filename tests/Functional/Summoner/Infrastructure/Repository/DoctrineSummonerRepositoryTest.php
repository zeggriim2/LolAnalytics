<?php

declare(strict_types=1);

namespace App\Tests\Functional\Summoner\Infrastructure\Repository;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;
use App\Tests\Factory\SummonerEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DoctrineSummonerRepositoryTest extends KernelTestCase
{
    use ResetDatabase;

    private SummonerRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->repository = $container->get(SummonerRepositoryInterface::class);
    }

    public function testSaveAndFindByPuuid(): void
    {
        // Given: a summoner domain model
        $puuid = Puuid::fromString('test-puuid-123456');
        $summoner = Summoner::create(
            $puuid,
            RiotId::create('TestPlayer', 'EUW'),
            1234,
            150,
            Platform::EUW1,
            new \DateTimeImmutable('2024-01-15 10:00:00'),
        );

        // When: saving the summoner
        $this->repository->save($summoner);

        // Then: we can retrieve it
        $found = $this->repository->findByPuuid($puuid);

        $this->assertNotNull($found);
        $this->assertSame('test-puuid-123456', $found->puuid()->value());
        $this->assertSame('TestPlayer', $found->riotId()->gameName());
        $this->assertSame('EUW', $found->riotId()->tagLine());
        $this->assertSame(1234, $found->profileIconId());
        $this->assertSame(150, $found->summonerLevel());
        $this->assertSame(Platform::EUW1, $found->platform());
    }

    public function testFindByPuuidReturnsNullWhenNotFound(): void
    {
        // Given: empty database
        // When: searching for a non-existent summoner
        $puuid = Puuid::fromString('non-existent-puuid');
        $found = $this->repository->findByPuuid($puuid);

        // Then: should return null
        $this->assertNull($found);
    }

    public function testExistsReturnsTrueWhenSummonerExists(): void
    {
        // Given: a summoner in database
        SummonerEntityFactory::createOne([
            'puuid' => 'existing-puuid-123',
        ]);

        // When: checking existence
        $exists = $this->repository->exists(Puuid::fromString('existing-puuid-123'));

        // Then: should return true
        $this->assertTrue($exists);
    }

    public function testExistsReturnsFalseWhenSummonerDoesNotExist(): void
    {
        // Given: empty database
        // When: checking existence
        $exists = $this->repository->exists(Puuid::fromString('non-existent-puuid'));

        // Then: should return false
        $this->assertFalse($exists);
    }

    public function testSaveUpdatesExistingSummoner(): void
    {
        // Given: an existing summoner in database
        SummonerEntityFactory::createOne([
            'puuid' => 'update-test-puuid',
            'gameName' => 'OldName',
            'tagLine' => 'OLD',
            'summonerLevel' => 100,
        ]);

        // When: saving with updated data
        $puuid = Puuid::fromString('update-test-puuid');
        $updatedSummoner = Summoner::create(
            $puuid,
            RiotId::create('NewName', 'NEW'),
            9999,
            200,
            Platform::EUW1,
            new \DateTimeImmutable(),
        );
        $this->repository->save($updatedSummoner);

        // Then: the summoner should be updated
        $found = $this->repository->findByPuuid($puuid);

        $this->assertNotNull($found);
        $this->assertSame('NewName', $found->riotId()->gameName());
        $this->assertSame('NEW', $found->riotId()->tagLine());
        $this->assertSame(200, $found->summonerLevel());
        $this->assertSame(9999, $found->profileIconId());
    }

    public function testFindByPuuidWithFactory(): void
    {
        // Given: a summoner created with factory
        SummonerEntityFactory::createOne([
            'puuid' => 'factory-test-puuid',
            'gameName' => 'FactoryPlayer',
            'tagLine' => 'FAC',
            'profileIconId' => 555,
            'summonerLevel' => 300,
            'platform' => 'euw1',
        ]);

        // When: finding by puuid
        $found = $this->repository->findByPuuid(Puuid::fromString('factory-test-puuid'));

        // Then: should return correct domain model
        $this->assertNotNull($found);
        $this->assertSame('FactoryPlayer', $found->riotId()->gameName());
        $this->assertSame('FAC', $found->riotId()->tagLine());
        $this->assertSame(555, $found->profileIconId());
        $this->assertSame(300, $found->summonerLevel());
    }
}
