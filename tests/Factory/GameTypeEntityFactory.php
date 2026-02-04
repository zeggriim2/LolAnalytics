<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameTypeEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<GameTypeEntity>
 */
final class GameTypeEntityFactory extends PersistentProxyObjectFactory
{
    private static int $counter = 0;

    public static function class(): string
    {
        return GameTypeEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'gameType' => 'GAMETYPE_' . ++self::$counter,
            'description' => self::faker()->sentence(),
        ];
    }

    public static function resetCounter(): void
    {
        self::$counter = 0;
    }

    public function matchedGame(): self
    {
        return $this->with(['gameType' => 'MATCHED_GAME', 'description' => 'Matched Game']);
    }

    public function customGame(): self
    {
        return $this->with(['gameType' => 'CUSTOM_GAME', 'description' => 'Custom Game']);
    }
}
