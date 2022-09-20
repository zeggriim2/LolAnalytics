<?php

namespace App\Tests\Command;

use App\Repository\VersionRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CommandVersionTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:version');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
    }
}
