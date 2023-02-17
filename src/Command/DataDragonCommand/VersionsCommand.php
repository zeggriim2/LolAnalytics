<?php

namespace App\Command\DataDragonCommand;

use App\Entity\Version;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;

#[AsCommand(
    name: 'app:versions',
    description: 'Ajoute les versions en Bdd.',
)]
final class VersionsCommand extends Command
{
    private DataDragonApi $dataDragonApi;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        parent::__construct();
        $this->dataDragonApi = new DataDragonApi();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $versions = $this->dataDragonApi->getVersions();
        $io->progressStart(count($versions));
        $countVersionAdd = 0;
        foreach ($versions as $version) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(Version::class)
                ->findOneBy(['code' => $version])) {
                $countVersionAdd++;
                $versionEntity = (new Version())
                    ->setCode($version);

                $this->managerRegistry->getManager()->persist($versionEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les Versions on été ajouté ($countVersionAdd)");

        return Command::SUCCESS;
    }
}
