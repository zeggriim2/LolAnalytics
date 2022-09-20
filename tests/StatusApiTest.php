<?php

namespace App\Tests;

use App\Services\API\LOL\LeagueOfLegends\DTO\Status\PlatformDataDto;
use App\Services\API\LOL\LeagueOfLegends\StatusApi;
use App\Tests\Traits\CheckStatusApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StatusApiTest extends KernelTestCase
{
    use CheckStatusApi;

    private ?StatusApi $statusApi;

    protected function setUp(): void
    {
        $this->statusApi = static::getContainer()->get(StatusApi::class);
    }

    public function testStatusSuccess()
    {
        $status = $this->statusApi->status();
        $this->assertInstanceOf(
            PlatformDataDto::class,
            $status
        );
        $this->checkPlatformDataDto($status);
    }
}
