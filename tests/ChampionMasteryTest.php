<?php

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\ChampionMasteryApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\ChampionMastery\ChampionMasteryDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChampionMasteryTest extends KernelTestCase
{
    private ChampionMasteryApi $championMasteryApi;

    public function setUp(): void
    {
        $this->championMasteryApi = static::getContainer()->get(ChampionMasteryApi::class);
    }

    public function testChampionMasteryBySummonerIdSuccess()
    {
        $championMasteryList = $this->championMasteryApi
            ->getChampionMasteryBySummonerId("tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4");


        if($championMasteryList > 0) {
            $this->assertIsArray($championMasteryList);

            foreach ($championMasteryList as $championMastery) {
                $this->assertInstanceOf(ChampionMasteryDto::class, $championMastery);
            }
        }
    }

    public function testChampionMasteryBySummonerIdForbidden()
    {
        $this->expectException(ForbiddenException::class);
        $championMasteryList = $this->championMasteryApi->getChampionMasteryBySummonerId('');
    }

    public function testChampionMasteryBySummonerIdAndByChampionSuccess()
    {
        $championMastery = $this->championMasteryApi->getChampionMasteryBySummonerIdAndByChampionId(
            "tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4",
            "222"
        );
        $this->assertInstanceOf(ChampionMasteryDto::class, $championMastery);
    }

    public function testChampionMasteryBySummonerIdAndByChampionForbiddenSummonerId()
    {
        $this->expectException(ForbiddenException::class);
        $championMasteryList = $this->championMasteryApi->getChampionMasteryBySummonerIdAndByChampionId(
            '',
            '222'
        );
    }

    public function testChampionMasteryBySummonerIdAndByChampionForbiddenChampionId()
    {
        $this->expectException(ForbiddenException::class);
        $championMasteryList = $this->championMasteryApi->getChampionMasteryBySummonerIdAndByChampionId(
            'tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4',
            ''
        );
    }

    public function testChampionMasteryScoreBySummonerIdSuccess()
    {
        $championMasteryScore = $this->championMasteryApi->getChampionMasteryScoreBySummonerId(
            "tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4"
        );

        $this->assertIsInt($championMasteryScore);
    }

    public function testChampionMasteryScoreBySummonerIdForbidden()
    {
        $this->expectException(ForbiddenException::class);
        $championMasteryScore = $this->championMasteryApi->getChampionMasteryScoreBySummonerId(
            ""
        );
    }
}