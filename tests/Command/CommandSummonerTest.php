<?php

namespace App\Tests\Command;

use App\Repository\InvocateurRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CommandSummonerTest extends KernelTestCase
{
    private Application $application;

    public function setUp(): void
    {
        $this->application = new Application(self::bootKernel());
        $this->application->setAutoExit(false);
    }

    public function testExecuteSuccess()
    {
        $command = $this->application->find('app:summoner');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['name'=> 'jarkalien']);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $outputArray = explode(' ', trim($output));
        $idLol = $outputArray[count($outputArray) - 2];
        $invocateur = static::getContainer()->get(InvocateurRepository::class)->findOneBy(['idLol' => $idLol]);
        $this->assertNotNull($invocateur);
    }

    public function testExecuteInvalid()
    {
        $command = $this->application->find('app:summoner');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['name'=> 'jarkalien']);

        $this->assertEquals(1,$commandTester->getStatusCode());
    }



}