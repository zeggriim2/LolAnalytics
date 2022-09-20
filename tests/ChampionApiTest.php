<?php

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\ChampionApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\Champion\ChampionInfoDTO;
use App\Tests\Traits\CheckChampionApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChampionApiTest extends KernelTestCase
{
    use CheckChampionApi;

    private ChampionApi $championApi;

    protected function setUp(): void
    {
        $this->championApi = static::getContainer()->get(ChampionApi::class);
    }

    public function testChampionRotationSuccess()
    {
        $championRotation = $this->championApi->getChampionRotation();
        $this->assertInstanceOf(ChampionInfoDTO::class, $championRotation);
        $this->checkChampionInfoDto($championRotation);
    }
}
