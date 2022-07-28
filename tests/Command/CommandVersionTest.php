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
        $nbVersionRepo = static::getContainer()->get(VersionRepository::class)->count([]);

        $output = $commandTester->getDisplay();
        $outputArray = explode(' ', trim($output));
        $nb = (int) $outputArray[1];

        $this->assertEquals($nb, $nbVersionRepo);
    }
}
