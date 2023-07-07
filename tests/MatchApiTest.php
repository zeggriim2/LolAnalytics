<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use App\Services\API\LOL\LeagueOfLegends\MatchApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MatchApiTest extends KernelTestCase
{
    private MatchApi $matchApi;

    protected function setUp(): void
    {
        $this->matchApi = static::getContainer()->get(MatchApi::class);
    }

    public function testMatchByPuuidSuccess(): void
    {
        $match = $this->matchApi->getMatchByPuuid(
            'NFLqmQ-TfqzILQI1aYhPTIBn6FG1Ox3QYT2sCGDRQNlEQC8MVIzkOjw2VAncGE70VF-L4ptfaUxEUw'
        );
        $this->assertIsArray($match);
    }

    public function testMatchByPuuidForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Puuid est vide');
        $this->matchApi->getMatchByPuuid('');
    }

    public function testMatchByIdSuccess(): void
    {
        $matchDetail = $this->matchApi->getMatchById('EUW1_5966722251');
        $this->assertInstanceOf(MatchDto::class, $matchDetail);
    }

    public function testMatchByIdForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Match ID est vide');
        $matchDetail = $this->matchApi->getMatchById('');
    }

    public function testMatchTimeLineByIdSuccess(): void
    {
        $matchDetail = $this->matchApi->getMatchTimeLineByMatchId('EUW1_5966722251');
        $this->assertIsArray($matchDetail);
    }

    public function testMatchTimeLineByIdForbiddenException(): void
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Match ID est vide');
        $matchDetail = $this->matchApi->getMatchTimeLineByMatchId('');
    }
}
