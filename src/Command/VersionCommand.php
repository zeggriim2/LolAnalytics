<?php

namespace App\Command;

use App\Entity\Version;
use App\Services\API\LOL\DataDragon\DataDragonApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:versions',
    description: 'Ajout les versions en BDD',
)]
class VersionCommand extends Command
{
    public function __construct(
        private DataDragonApi $dataDragonApi,
        private EntityManagerInterface $doctrine,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $versions = $this->dataDragonApi->getVersions();

        $count = 0;
        if($versions !== null){
            foreach (array_reverse($versions) as $version) {
                $versionRepo = $this->doctrine->getRepository(Version::class)->findOneBy(['name' => $version]);
                if (null === $versionRepo) {
                    $versionsEntity = (new Version())
                        ->setName($version)
                    ;
                    $this->doctrine->persist($versionsEntity);
                    ++$count;
                }
            }
        }
        $this->doctrine->flush();

        if (0 === $count) {
            $phrase = "Aucun élément de version n'a été ajouté.";
        } else {
            $phrase = "$count version ont été ajouté en bdd.";
        }

        $io->success($phrase);

        return Command::SUCCESS;
    }
}
