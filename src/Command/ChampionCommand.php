<?php

namespace App\Command;

use App\Command\Traits\ChampionTrait;
use App\Entity\Champion;
use App\Entity\InfoChampion;
use App\Entity\StatChampion;
use App\Entity\Version;
use App\Services\API\LOL\DataDragon\DataDragonApi;
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

    use ChampionTrait;

    private int $count = 0;
    private int $countChampionApi = 0;

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
            ->addArgument(
                'version',
                InputArgument::OPTIONAL,
                'Si on veut préciser un version particulière.'
            )
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Recupération de l'argument version
        $versionInput = $input->getArgument('version');

        /** @var array<array-key, string> $versionsApi */
        $versionsApi = $this->dataDragonApi->getVersions();

        $version = null;

        // Verifi & récupere l'entity Version saisie ou non dans les paramètre Command
        if($versionInput && $version = $this->chechVersionInDatabase($versionInput) ){

            /** @var string|bool $keyVersionApi */
            $keyVersionApi = $versionsApi ? array_search($versionInput,$versionsApi) : false;
            if(is_bool($keyVersionApi)){
                $io->error("La version $versionInput n'existe pas");
                return Command::FAILURE;
            }
            $version = $this->doctrine->getRepository(Version::class)
                ->findOneBy(['name' => $versionsApi[$keyVersionApi]]);
        }else if($versionsApi) {
            $version = $this->doctrine->getRepository(Version::class)
                ->findOneBy(['name' => $versionsApi[0]]);
        }

        if($version) {
            $championsApi = $this->dataDragonApi->getChampions($version->getName());
            if(is_null($championsApi) || !array_key_exists('data', $championsApi)) throw new \Exception('No key Data');
            $keyChampions = $this->doctrine->getRepository(Champion::class)->AllKeyForVersion($version);

            foreach ($championsApi['data'] as $championApi) {
                if(!$this->checkExisteInBdd($championApi, $keyChampions)){
                    $this->count++;
                    $this->createChampion($championApi, $version);
                }
            }
        }

        $this->doctrine->flush();

        $io->success($this->phraseRetour());

        return Command::SUCCESS;
    }

    /**
     * @param array<string, mixed> $championApi
     * @return InfoChampion
     */
    public function createInfoChampion(array $championApi): InfoChampion
    {
        $infoChampion = (new InfoChampion())
            ->setAttack($championApi['info']['attack'])
            ->setDifficulty($championApi['info']['difficulty'])
            ->setDefense($championApi['info']['defense'])
            ->setMagic($championApi['info']['magic'])
        ;
        $this->doctrine->persist($infoChampion);
        return $infoChampion;
    }

    private function phraseRetour(): string
    {
        if (0 === $this->count) {
            $phrase = "Aucun élément de champion n'a été ajouté ({$this->countChampionApi}).";
        } else {
            $phrase = "{$this->count} champion ont été ajouté en bdd ({$this->countChampionApi}).";
        }
        return $phrase;
    }

    /**
     * @param array<string, mixed> $championApi
     * @param array<array-key, string> $keyChampions
     * @return bool
     */
    private function checkExisteInBdd(array $championApi,array $keyChampions): bool
    {
        return in_array($championApi['key'],$keyChampions);
    }

    /**
     * @param string $versionInput
     * @return Version|null
     */
    private function chechVersionInDatabase(string $versionInput): ?Version
    {
        return  $this->doctrine->getRepository(Version::class)
            ->findOneBy(['name' => $versionInput]);

    }
}
