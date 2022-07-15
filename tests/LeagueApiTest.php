<?php

namespace App\Tests;

use App\Services\API\LOL\DataDragon\Division;
use App\Services\API\LOL\DataDragon\Queue;
use App\Services\API\LOL\DataDragon\Tier;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueListDTO;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use App\Services\API\LOL\LeagueOfLegends\Exception\LeagueArgumentException;
use App\Services\API\LOL\LeagueOfLegends\LeagueApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LeagueApiTest extends KernelTestCase
{
    private LeagueApi $leagueApi;


    public function setUp(): void
    {
        $this->leagueApi = static::getContainer()->get(LeagueApi::class);
    }

    // League By Summoner ID Start
    public function testLeagueBySummonerIdSuccess()
    {
        $leaguesSummonerId =
            $this->leagueApi->leagueBySummonerId('tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4');

        $this->assertIsArray($leaguesSummonerId);

        foreach ($leaguesSummonerId as $leagueSummonerId) {
            $this->assertInstanceOf(LeagueEntryDTO::class,$leagueSummonerId);
        }
    }

    public function testLeagueBySummonerIdForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage("Summoner ID est vide");
        $leaguesSummonerId =
            $this->leagueApi->leagueBySummonerId('');
    }
    // League By Summoner ID End

    // League Challenger By Queue Start
    public function testLeagueChallengerByQueueSuccess()
    {
        $leagueChallenger = $this->leagueApi->leagueChallengerByQueue(Queue::RANKED_SOLO);
        $this->assertInstanceOf(LeagueListDTO::class, $leagueChallenger);
    }

    public function testLeagueChallengerByQueueForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $leagueChallenger = $this->leagueApi->leagueChallengerByQueue("");
    }

    public function testLeagueChallengerByQueueLeagueArgumentException()
    {
        $this->expectException(LeagueArgumentException::class);
        $leagueChallenger = $this->leagueApi->leagueChallengerByQueue("zerhzaterh");
    }
    // League Challenger By Queue End

    // League Grand Master By Queue Start
    public function testLeagueGrandMasterByQueueSuccess()
    {
        $leagueChallenger = $this->leagueApi->leagueGrandMasterByQueue(Queue::RANKED_SOLO);
        $this->assertInstanceOf(LeagueListDTO::class, $leagueChallenger);
    }

    public function testLeagueGrandMasterByQueueForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $leagueChallenger = $this->leagueApi->leagueGrandMasterByQueue("");
    }

    public function testLeagueGrandMasterByQueueLeagueArgumentException()
    {
        $this->expectException(LeagueArgumentException::class);
        $leagueChallenger = $this->leagueApi->leagueGrandMasterByQueue("zerhzaterh");
    }
    // League Grand Master By Queue End

    // League Master By Queue Start
    public function testLeagueMasterByQueueSuccess()
    {
        $leagueMaster = $this->leagueApi->leagueMasterByQueue(Queue::RANKED_SOLO);
        $this->assertInstanceOf(LeagueListDTO::class, $leagueMaster);
    }

    public function testLeagueMasterByQueueForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $leagueMaster = $this->leagueApi->leagueMasterByQueue("");
    }

    public function testLeagueMasterByQueueLeagueArgumentException()
    {
        $this->expectException(LeagueArgumentException::class);
        $leagueMaster = $this->leagueApi->leagueMasterByQueue("zerhzaterh");
    }
    // League Master By Queue End

    // League By League ID Start
    public function testLeagueByLeagueIdSuccess()
    {
        $league = $this->leagueApi->leagueByLeagueId("86940884-763f-478a-860d-a98c57a85102");
        $this->assertInstanceOf(LeagueListDTO::class, $league);
    }

    public function testLeagueByLeagueIdForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $league = $this->leagueApi->leagueByLeagueId("");
    }
    // League Grand Master By Queue End

    // League By Queue By Tier By Division Start
    public function testLeagueByQueueByTierByDivisionSuccess()
    {
        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            Queue::RANKED_SOLO,
            Tier::TIER_GOLD,
            Division::DIVISION_I
        );

        $this->assertIsArray($leagueList);

        foreach ($leagueList as $league) {
            $this->assertInstanceOf(LeagueEntryDTO::class, $league);
        }
    }

    public function testLeagueByQueueByTierByDivisionExceptionForbidden()
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage("Queue/Tier/Division est vide");
        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            "",
            Tier::TIER_GOLD,
            Division::DIVISION_I
        );

        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            Queue::RANKED_SOLO,
            "",
            Division::DIVISION_I
        );

        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            Queue::RANKED_SOLO,
            Tier::TIER_GOLD,
            ""
        );
    }

    public function testLeagueByQueueByTierByDivisionExceptionArgumentException()
    {
        $this->expectException(LeagueArgumentException::class);
        $this->expectExceptionMessage("N'existe pas dans les différents éléments Queue/Tier/Division");
        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            "zregarehgae",
            Tier::TIER_GOLD,
            Division::DIVISION_I
        );
    }
    // League By Queue By Tier By Division End
}