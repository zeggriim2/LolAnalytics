<?php

namespace App\Command;

use App\Entity\Champion;
use App\Entity\InfoChampion;
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
            ->addArgument('version', InputArgument::OPTIONAL, 'Si on veut préciser un version particulière.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Recupération de l'argument version
        $versionInput = $input->getArgument('version');

        if($versionInput){

            // On récupère les versions par API
            $versionsApi = $this->dataDragonApi->getVersions();
            // Check si la version existe
            $keyVersionApi = array_search($versionInput,$versionsApi);
            if($keyVersionApi === false){
                $io->error("La version $versionInput n'existe pas");
                return Command::FAILURE;
            }else{
                $version = $this->doctrine->getRepository(Version::class)
                    ->findOneBy(['name' => $versionsApi[$keyVersionApi]]);
                if($version){
                    $this->createChampion($version);
                }
            }
        }else{
            // On récupère la dernière version
            $versionApi = $this->dataDragonApi->getVersions();
            /** @var Version|null $version */
            $version = $this->doctrine->getRepository(Version::class)->findOneBy(['name' => $versionApi[0]]);
            if($version){
                $this->createChampion($version);
            }
        }

        $this->doctrine->flush();

        $io->success($this->phraseRetour());

        return Command::SUCCESS;
    }

    /**
     * @param Version $version
     * @return void
     */
    private function createChampion(Version $version): void
    {
        $championsApi = $this->dataDragonApi->getChampions($version->getName());
        $this->countChampionApi = count($championsApi['data']);
        $keyChampions = $this->doctrine->getRepository(Champion::class)->AllKeyForVersion($version);
        foreach ($championsApi['data'] as $championApi){
            if(!$this->checkExisteInBdd($championApi, $keyChampions)){
                $this->count++;
                $champion = (new Champion())
                    ->setIdName($championApi['id'])
                    ->setName($championApi['name'])
                    ->setKey($championApi['key'])
                    ->setTitle($championApi['title'])
                    ->setPartype($championApi['partype'] ?: null)
                    ->setVersion($version)
                ;

                // On crée info Champion s'il n'existe pas
                $infoChampion = (new InfoChampion())
                    ->setAttack($championApi['info']['attack'])
                    ->setDifficulty($championApi['info']['difficulty'])
                    ->setDefense($championApi['info']['defense'])
                    ->setMagic($championApi['info']['magic'])
                ;
                $this->doctrine->persist($infoChampion);
                $champion->setInfoChampion($infoChampion);

                $this->doctrine->persist($champion);
            }
        }


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

    private function checkExisteInBdd(array $championApi,array $keyChampions): bool
    {
        return in_array($championApi['key'],$keyChampions);
    }

}
