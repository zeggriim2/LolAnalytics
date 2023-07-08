<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\API\LOL\DataDragon\Enum\Division;
use App\Services\API\LOL\DataDragon\Enum\Queue;
use App\Services\API\LOL\DataDragon\Enum\Tier;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueListDTO;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use App\Services\API\LOL\LeagueOfLegends\Exception\LeagueArgumentException;
use App\Services\API\LOL\LeagueOfLegends\LeagueApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LeagueApiTest extends KernelTestCase
{
    private LeagueApi $leagueApi;

    protected function setUp(): void
    {
        $this->leagueApi = static::getContainer()->get(LeagueApi::class);
    }

    // League By Summoner ID Start
    public function testLeagueBySummonerIdSuccess(): void
    {
        $leaguesSummonerId =
            $this->leagueApi->leagueBySummonerId('tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4');

        $this->assertIsArray($leaguesSummonerId);

        foreach ($leaguesSummonerId as $leagueSummonerId) {
            $this->assertInstanceOf(LeagueEntryDTO::class, $leagueSummonerId);
        }
    }

    public function testLeagueBySummonerIdForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Summoner ID est vide');
        $leaguesSummonerId =
            $this->leagueApi->leagueBySummonerId('');
    }
    // League By Summoner ID End

    // League Challenger By Queue Start
    public function testLeagueChallengerByQueueSuccess(): void
    {
        $leagueChallenger = $this->leagueApi->leagueChallengerByQueue(Queue::RANKED_SOLO);
        $this->assertInstanceOf(LeagueListDTO::class, $leagueChallenger);
        dd($leagueChallenger->getEntries());
        $this->assertContainsOnlyInstancesOf(LeagueListDTO::class, $leagueChallenger->getEntries());
    }

    public function testLeagueChallengerByQueueForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $leagueChallenger = $this->leagueApi->leagueChallengerByQueue('');
    }

    public function testLeagueChallengerByQueueLeagueArgumentException(): void
    {
        $this->expectException(LeagueArgumentException::class);
        $leagueChallenger = $this->leagueApi->leagueChallengerByQueue('zerhzaterh');
    }
    // League Challenger By Queue End

    // League Grand Master By Queue Start
    public function testLeagueGrandMasterByQueueSuccess(): void
    {
        $leagueChallenger = $this->leagueApi->leagueGrandMasterByQueue(Queue::RANKED_SOLO);
        $this->assertInstanceOf(LeagueListDTO::class, $leagueChallenger);
    }

    public function testLeagueGrandMasterByQueueForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $leagueChallenger = $this->leagueApi->leagueGrandMasterByQueue('');
    }

    public function testLeagueGrandMasterByQueueLeagueArgumentException(): void
    {
        $this->expectException(LeagueArgumentException::class);
        $leagueChallenger = $this->leagueApi->leagueGrandMasterByQueue('zerhzaterh');
    }
    // League Grand Master By Queue End

    // League Master By Queue Start
    public function testLeagueMasterByQueueSuccess(): void
    {
        $leagueMaster = $this->leagueApi->leagueMasterByQueue(Queue::RANKED_SOLO);
        $this->assertInstanceOf(LeagueListDTO::class, $leagueMaster);
    }

    public function testLeagueMasterByQueueForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $leagueMaster = $this->leagueApi->leagueMasterByQueue('');
    }

    public function testLeagueMasterByQueueLeagueArgumentException(): void
    {
        $this->expectException(LeagueArgumentException::class);
        $leagueMaster = $this->leagueApi->leagueMasterByQueue('zerhzaterh');
    }
    // League Master By Queue End

    // League By League ID Start
    public function testLeagueByLeagueIdSuccess(): void
    {
        $league = $this->leagueApi->leagueByLeagueId('86940884-763f-478a-860d-a98c57a85102');
        $this->assertInstanceOf(LeagueListDTO::class, $league);
    }

    public function testLeagueByLeagueIdForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $league = $this->leagueApi->leagueByLeagueId('');
    }
    // League Grand Master By Queue End

    // League By Queue By Tier By Division Start
    public function testLeagueByQueueByTierByDivisionSuccess(): void
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

    public function testLeagueByQueueByTierByDivisionExceptionForbidden(): void
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Queue/Tier/Division est vide');
        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            '',
            Tier::TIER_GOLD,
            Division::DIVISION_I
        );

        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            Queue::RANKED_SOLO,
            '',
            Division::DIVISION_I
        );

        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            Queue::RANKED_SOLO,
            Tier::TIER_GOLD,
            ''
        );
    }

    public function testLeagueByQueueByTierByDivisionExceptionArgumentException(): void
    {
        $this->expectException(LeagueArgumentException::class);
        $this->expectExceptionMessage("N'existe pas dans les différents éléments Queue/Tier/Division");
        $leagueList = $this->leagueApi->leagueByQueueByTierByDivision(
            'zregarehgae',
            Tier::TIER_GOLD,
            Division::DIVISION_I
        );
    }
    // League By Queue By Tier By Division End
}
