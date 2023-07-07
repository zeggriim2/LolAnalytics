<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\ChampionApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\Champion\ChampionInfoDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChampionApiTest extends KernelTestCase
{
    private ChampionApi $championApi;

    protected function setUp(): void
    {
        $this->championApi = static::getContainer()->get(ChampionApi::class);
    }

    public function testChampionRotationSuccess(): void
    {
        $championRotation = $this->championApi->getChampionRotation();
        $this->assertInstanceOf(ChampionInfoDTO::class, $championRotation);
    }
}
