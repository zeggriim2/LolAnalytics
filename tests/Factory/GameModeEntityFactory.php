<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameModeEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<GameModeEntity>
 */
final class GameModeEntityFactory extends PersistentProxyObjectFactory
{
    private static int $counter = 0;

    public static function class(): string
    {
        return GameModeEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'gameMode' => 'GAMEMODE_' . ++self::$counter,
            'description' => self::faker()->sentence(),
        ];
    }

    public static function resetCounter(): void
    {
        self::$counter = 0;
    }

    public function classic(): self
    {
        return $this->with(['gameMode' => 'CLASSIC', 'description' => 'Classic Summoner\'s Rift']);
    }

    public function aram(): self
    {
        return $this->with(['gameMode' => 'ARAM', 'description' => 'ARAM']);
    }
}
