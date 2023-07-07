<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;
use App\Services\API\LOL\LeagueOfLegends\SummonerApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SummonerApiTest extends KernelTestCase
{
    public ?SummonerApi $summonerApi;

    protected function setUp(): void
    {
        $this->summonerApi = static::getContainer()->get(SummonerApi::class);
    }

    public function testSummonerByNameSuccess(): void
    {
        $this->assertInstanceOf(
            SummonerDTO::class,
            $this->summonerApi->summonerBySummonerName('jarkalien')
        );
    }

    public function testSummonerByNameBad(): void
    {
        $this->assertNull($this->summonerApi->summonerBySummonerName('rzgakrj'));
    }

    public function testSummonerByNameEmpty(): void
    {
        $this->assertNull($this->summonerApi->summonerBySummonerName(''));
    }

    public function testSummonerByAccountIdSuccess(): void
    {
        $this->assertInstanceOf(
            SummonerDTO::class,
            $this->summonerApi->summonerByAccountId('vppsXdQUkIWjEkVAVLjdy4tgz0Ogy08aj_nzJIajal5JdQ')
        );
    }

    public function testSummonerByAccountIdBad(): void
    {
        $this->assertNull($this->summonerApi->summonerByAccountId('rzgakrj'));
    }

    public function testSummonerByAccountIdEmpty(): void
    {
        $this->assertNull($this->summonerApi->summonerByAccountId(''));
    }

    public function testSummonerByPuuidSuccess(): void
    {
        $this->assertInstanceOf(
            SummonerDTO::class,
            $this->summonerApi->SummonerByPuuid(
                'NFLqmQ-TfqzILQI1aYhPTIBn6FG1Ox3QYT2sCGDRQNlEQC8MVIzkOjw2VAncGE70VF-L4ptfaUxEUw'
            )
        );
    }

    public function testSummonerByPuuidBad(): void
    {
        $this->assertNull($this->summonerApi->SummonerByPuuid('rzgakrj'));
    }

    public function testSummonerByPuuidEmpty(): void
    {
        $this->assertNull($this->summonerApi->SummonerByPuuid(''));
    }

    public function testSummonerBySummonerIdSuccess(): void
    {
        $this->assertInstanceOf(
            SummonerDTO::class,
            $this->summonerApi->SummonerBySummonerId('tSmVTVjydJYj5gbjMy8IhFkyMpgWhc4JNdH4ZbqHal3maT4')
        );
    }

    public function testSummonerBySummonerIdBad(): void
    {
        $this->assertNull($this->summonerApi->SummonerBySummonerId('rzgakrj'));
    }

    public function testSummonerBySummonerIdEmpty(): void
    {
        $this->assertNull($this->summonerApi->SummonerBySummonerId(''));
    }
}
