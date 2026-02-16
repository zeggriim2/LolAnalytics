<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\VersionEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<VersionEntity>
 */
final class VersionEntityFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return VersionEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'version' => self::faker()->numerify('#.##.#'),
        ];
    }
}
