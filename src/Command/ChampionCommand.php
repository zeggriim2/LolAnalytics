<?php

namespace App\Command;

use App\Command\Traits\ChampionTrait;
use App\Entity\Champion;
use App\Entity\Image;
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

        $versionsApi = $this->dataDragonApi->getVersions();

        if($versionInput){
            /** @var int|bool $keyVersionApi */
            $keyVersionApi = $versionsApi ? array_search($versionInput,$versionsApi) : false;
            if($keyVersionApi === false){
                $io->error("La version $versionInput n'existe pas");
                return Command::FAILURE;
            }
            $version = $this->doctrine->getRepository(Version::class)
                ->findOneBy(['name' => $versionsApi[$keyVersionApi]]);
        }else{
            $version = $this->doctrine->getRepository(Version::class)
                ->findOneBy(['name' => $versionsApi[0]]);
        }

        if ($version) $this->createChampion($version);
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
        /** @var array<string, mixed> $championsApi */
        $championsApi = $this->dataDragonApi->getChampions($version->getName());
        if(!array_key_exists('data', $championsApi)){
            //error
            throw new \Exception('No key Data');
        }
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
                $infoChampion = $this->createInfoChampion($championApi);

                // On crée stat Champion s'il n'existe pas
                $statChampion = $this->createStatChampion($championApi);

                // On crée l'image Champion s'il n'existe pas
                $imageChampion = $this->createImageChampion($championApi);

                $champion->setInfoChampion($infoChampion);
                $champion->setStatChampion($statChampion);
                $champion->setImage($imageChampion);

                $this->doctrine->persist($champion);
            }
        }
    }

    /**
     * @param array<string, mixed> $championApi
     * @return StatChampion
     */
    private function createStatChampion(array $championApi): StatChampion
    {
        $statChampion = (new StatChampion())
            ->setHp($championApi['stats']['hp'])
            ->setHpperlevel($championApi['stats']['hpperlevel'])
            ->setMp($championApi['stats']['mp'])
            ->setMpperlevel($championApi['stats']['mpperlevel'])
            ->setMovespeed($championApi['stats']['movespeed'])
            ->setArmor($championApi['stats']['armor'])
            ->setArmorperlevel($championApi['stats']['armorperlevel'])
            ->setSpellblock($championApi['stats']['spellblock'])
            ->setSpellblockperlevel($championApi['stats']['spellblockperlevel'])
            ->setAttackrange($championApi['stats']['attackrange'])
            ->setHpregen($championApi['stats']['hpregen'])
            ->setHpregenperlevel($championApi['stats']['hpregenperlevel'])
            ->setMpregen($championApi['stats']['mpregen'])
            ->setMpregenperlevel($championApi['stats']['mpregenperlevel'])
            ->setCrit($championApi['stats']['crit'])
            ->setCritperlevel($championApi['stats']['critperlevel'])
            ->setAttackdamage($championApi['stats']['attackdamage'])
            ->setAttackdamageperlevel($championApi['stats']['attackdamageperlevel'])
            ->setAttackspeedperlevel($championApi['stats']['attackspeedperlevel'])
            ->setAttackspeed($championApi['stats']['attackspeed'])
        ;
        $this->doctrine->persist($statChampion);
        return $statChampion;
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

    /**
     * @param array<string, mixed> $championApi
     * @return Image
     */
    public function createImageChampion(array $championApi): Image
    {

        $ImageChampion = (new Image())
            ->setComplete($championApi['image']['full'])
            ->setGroupe($championApi['image']['sprite'])
            ->setSprite($championApi['image']['group'])
            ->setX($championApi['image']['x'])
            ->setY($championApi['image']['y'])
            ->setW($championApi['image']['w'])
            ->setH($championApi['image']['h'])
        ;

        return $ImageChampion;
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

}
