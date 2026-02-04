<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\QueueEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<QueueEntity>
 */
final class QueueEntityFactory extends PersistentProxyObjectFactory
{
    private static int $counter = 0;

    public static function class(): string
    {
        return QueueEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'queueId' => 10000 + ++self::$counter,
            'map' => 'Map',
            'description' => self::faker()->optional()->sentence(),
            'notes' => self::faker()->optional()->sentence(),
        ];
    }

    public static function resetCounter(): void
    {
        self::$counter = 0;
    }

    public function rankedSoloDuo(): self
    {
        return $this->with(['queueId' => 420, 'map' => "Summoner's Rift", 'description' => 'Ranked Solo/Duo']);
    }

    public function aram(): self
    {
        return $this->with(['queueId' => 450, 'map' => 'Howling Abyss', 'description' => 'ARAM']);
    }
}
