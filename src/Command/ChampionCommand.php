<?php

namespace App\Command;

use App\Entity\Champion;
use App\Entity\Image;
use App\Entity\InfoChampion;
use App\Entity\Version;
use App\Services\API\LOL\DataDragon\DataDragonApi;
use App\Traits\Command\CreateObjectChampionTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:champions',
    description: 'Ajout des Champions en BDD',
)]
class ChampionCommand extends Command
{
    use CreateObjectChampionTrait;

    private int $count = 0;
    private int $batchSize = 20;
    private int $inscrementBatch = 0;

    public function __construct(
        private DataDragonApi $dataDragonApi,
        private EntityManagerInterface $doctrine,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument("version", InputArgument::OPTIONAL, "Si on veut préciser un version particulière.")
            ->addOption('all-version',null,InputOption::VALUE_NEGATABLE,"Si on veut récupérer tout les champions de toutes les versions")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $allVersionInput = $input->getOption('all-version');


        if($allVersionInput) {
            $versions = $this->doctrine->getRepository(Version::class)->findAll();
            foreach($versions as $version) {
                $champions = $this->dataDragonApi->getChampions($version->getName());
                if($champions !== null) {
                    $this->addChampions($champions, $version);
                }
            }
        }else{
            $champions = $this->dataDragonApi->getChampions();
            if($champions !== null){
                $versionLabel = $input->getArgument('version') ?: $champions['version'];
                /** @var Version|null $version */
                $version = $this->doctrine->getRepository(Version::class)->findOneBy(['name'=> $versionLabel]);
                // Si la version n'est pas trouvé en BDD
                if($version === null) {
                    $io->error("La version ${versionLabel} n'existe pas en Bdd");
                    return Command::FAILURE;
                }

                $this->addChampions($champions, $version);
            }
        }

        $this->doctrine->flush();
        $this->doctrine->clear();

        if (0 === $this->count) {
            $phrase = "Aucun élément de champion n'a été ajouté.";
        } else {
            $phrase = "{$this->count} champion ont été ajouté en bdd.";
        }

        $io->success($phrase);

        return Command::SUCCESS;
    }

    /**
     * @param mixed[] $champions
     * @param Version $version
     * @return void
     */
    private function addChampions(array $champions,Version $version): void
    {
        foreach ($champions["data"] as $champion) {
            // On verifie si le champion n'existe pas pour la version concerné
            $championRepo = $this->doctrine->getRepository(Champion::class)
                ->findOneBy(
                    [
                        'version' => $version,
                        'idName'    => $champion['id']
                    ]
                );

            if($championRepo === null) {
                $this->count++;
                $this->createChampion($champion, $version);
            }
        }
    }
}
