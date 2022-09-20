<?php

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use App\Services\API\LOL\LeagueOfLegends\MatchApi;
use App\Tests\Traits\CheckMatchApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MatchApiTest extends KernelTestCase
{
    use CheckMatchApi;

    private MatchApi $matchApi;

    protected function setUp(): void
    {
        $this->matchApi = static::getContainer()->get(MatchApi::class);
    }

    public function testMatchByPuuidSuccess()
    {
        $match = $this->matchApi->getMatchByPuuid(
            'NFLqmQ-TfqzILQI1aYhPTIBn6FG1Ox3QYT2sCGDRQNlEQC8MVIzkOjw2VAncGE70VF-L4ptfaUxEUw'
        );
        $this->assertIsArray($match);
    }

    public function testMatchByPuuidForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Puuid est vide');
        $this->matchApi->getMatchByPuuid('');
    }

    public function testMatchByIdSuccess()
    {
        $matchDetail = $this->matchApi->getMatchById('EUW1_5966722251');
        $this->assertInstanceOf(MatchDto::class, $matchDetail);
        $this->checkMatchDto($matchDetail);
    }

    private function checkMatchDto(MatchDto $matchDto)
    {
        $this->checkInfoDto($matchDto->getInfo());
        $this->checkMetadataDto($matchDto->getMetadata());
    }



    public function testMatchByIdForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Match ID est vide');
        $matchDetail = $this->matchApi->getMatchById('');
    }

    public function testMatchTimeLineByIdSuccess()
    {
        $matchDetail = $this->matchApi->getMatchTimeLineByMatchId('EUW1_5966722251');
        $this->assertIsArray($matchDetail);
    }

    public function testMatchTimeLineByIdForbiddenException()
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Match ID est vide');
        $matchDetail = $this->matchApi->getMatchTimeLineByMatchId('');
    }
}
