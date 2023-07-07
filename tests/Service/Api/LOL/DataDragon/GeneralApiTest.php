<?php

declare(strict_types=1);

namespace App\Tests\Service\Api\LOL\DataDragon;

use App\Services\API\LOL\DataDragon\GeneralApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GeneralApiTest extends KernelTestCase
{
    private GeneralApi $generalApi;

    protected function setUp(): void
    {
        $this->generalApi = static::getContainer()->get(GeneralApi::class);
    }

    public function testGetSeason(): void
    {
        $gameModes = $this->generalApi->getSeason();
        self::assertIsArray($gameModes);
    }

    public function testGetMaps(): void
    {
        $gameModes = $this->generalApi->getMaps();
        self::assertIsArray($gameModes);
    }

    public function testGetGameModes(): void
    {
        $gameModes = $this->generalApi->getGameModes();
        self::assertIsArray($gameModes);
    }

    public function testGetGameTypes(): void
    {
        $gameModes = $this->generalApi->getGameTypes();
        self::assertIsArray($gameModes);
    }
}
