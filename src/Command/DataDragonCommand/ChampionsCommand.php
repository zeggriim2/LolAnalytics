<?php

namespace App\Command\DataDragonCommand;

use App\Entity\Champion;
use App\Entity\GameMode;
use App\Entity\Image;
use App\Entity\Passive;
use App\Entity\Skin;
use App\Entity\Spell;
use App\Entity\Stat;
use App\Entity\Tag;
use App\Entity\Version;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;
use Zeggriim\RiotApiDatadragon\Enum\TypeReturn;
use Zeggriim\RiotApiDatadragon\Model\Champion\ChampionDetail;
use Zeggriim\RiotApiDatadragon\Model\Champion\Skin as SkinRiotApi;
use Zeggriim\RiotApiDatadragon\Model\General\Image as ImageRiotApi;
use Zeggriim\RiotApiDatadragon\Model\Champion\Passive as PassiveRiotApi;
use Zeggriim\RiotApiDatadragon\Model\Champion\Stats as StatsRiotApi;
use Zeggriim\RiotApiDatadragon\Model\Champion\Spell as SpellRiotApi;

#[AsCommand(
    name: 'app:champions',
    description: 'Ajoute des champions en Bdd.',
)]
final class ChampionsCommand extends Command
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
        $this
            ->addArgument('version', InputArgument::REQUIRED, "Numéro de version")
            ->addOption(
                name:"lastVersion",
                shortcut: "last",
                description: "Récupère les infos des champions par rapport à la dernière version"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $versionArgument = $input->getArgument('version');

        /** Ajoute les champions à partir de l'argument saisie */
        if($versionArgument) {
            $version = $this->checkVersion($versionArgument);
            if(is_null($version)) {
                $io->error("La version $versionArgument n'existe pas.");
                return Command::FAILURE;
            }

            $this->dataDragonApi->setLang('fr_FR');
            $this->addChampionsInBdd($version, $io);
        }

        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les Champions on été ajoutées (TODO)");

        return Command::SUCCESS;
    }


    private function checkVersion(string $version): ?Version
    {
        return $this->managerRegistry->getRepository(Version::class)->findOneBy(['code' => $version]);
    }

    private function addChampionsInBdd(
        Version $version,
        SymfonyStyle $io,
        int $batchSize = 20,
        int $i = 0
    )
    {
        $this->dataDragonApi->setVersion($version->getCode());
        $champions = $this->dataDragonApi->getChampions();
        $championsName = array_keys($champions['data']);
        $io->progressStart(count($championsName));
        foreach ($championsName as $championName) {
            $io->progressAdvance();
            $champion = $this->managerRegistry->getRepository(Champion::class)
                    ->findOneBy(['idLol' => ucfirst($championName), 'version' => $version]);

            if(is_null($champion)) {
                $i++;
                $championApi = $this->dataDragonApi->getChampion($championName, TypeReturn::RETURN_OBJET);
                /** @var ChampionDetail $championApi */
                $championApi = $championApi[array_key_first($championApi)];
                $champion = $this->createChampion($version, $championApi);
                $this->managerRegistry->getManager()->persist($champion);
                if(($i % $batchSize) === 0) {
                    $this->managerRegistry->getManager()->flush();
                    $this->managerRegistry->getManager()->detach($champion);
                }
            }
        }
    }

    private function createChampion(Version $version,ChampionDetail $championApi): Champion
    {
        $champion = (new Champion())
            ->setVersion($version)
            ->setIdLol($championApi->getId())
            ->setKey((string)$championApi->getKey())
            ->setName($championApi->getName())
            ->setTitle($championApi->getTitle())
            ->setLore($championApi->getLore())
            ->setBlurb($championApi->getBlurb())
            ->setPartype($championApi->getPartype())
            ->setAttack($championApi->getInfo()->getAttack())
            ->setDefense($championApi->getInfo()->getDefense())
            ->setDifficulty($championApi->getInfo()->getDifficulty())
            ->setMagic($championApi->getInfo()->getMagic())

            ;

        $champion->setImage( $this->managerRegistry->getRepository(Image::class)
            ->checkDataExistWhithApi($championApi->getImage()) ?? $this->createImage($championApi->getImage())
        );

        $champion->setPassive(
            $this->managerRegistry->getRepository(Passive::class)->findOneBy([
                'name' => $championApi->getPassive()->getName(),
                'description' => $championApi->getPassive()->getDescription()
            ]) ?? $this->createPassive($championApi->getPassive())
        );

        $champion->setStat(
            $this->managerRegistry->getRepository(Stat::class)
                ->checkDataExistWhithApi($championApi->getStats())
            ?? $this->createStat($championApi->getStats())
        );

        foreach($championApi->getSkins() as $skinApi) {
            $champion->addSkin($this->managerRegistry->getRepository(Skin::class)->findOneBy([
                'idLol' => $skinApi->getId()
            ]) ?? $this->createSkin($skinApi));
        }

        foreach ($championApi->getTags() as $tag) {
            $champion->addTag($this->managerRegistry->getRepository(Tag::class)->findOneBy([
                'name' => $tag
            ]) ?? $this->createTag($tag));
        }

        foreach ($championApi->getSpells() as $spell) {
            $champion->addSpell($this->createSpell($spell));
        }

        return $champion;
    }

    private function createSkin(SkinRiotApi $skinApi): Skin
    {
        return (new Skin())
            ->setIdLol($skinApi->getId())
            ->setNum($skinApi->getNum())
            ->setName($skinApi->getName())
            ->setChromas($skinApi->isChromas())
            ;
    }

    private function createImage(ImageRiotApi $imageApi): Image
    {
        return (new Image())
            ->setFullname($imageApi->getFull())
            ->setGroupe($imageApi->getGroup())
            ->setSprite($imageApi->getSprite())
            ->setX($imageApi->getX())
            ->setY($imageApi->getY())
            ->setW($imageApi->getW())
            ->setH($imageApi->getH())
        ;
    }

    private function createPassive(PassiveRiotApi $passiveApi): Passive
    {
        return (new Passive)
            ->setName($passiveApi->getName())
            ->setDescription($passiveApi->getDescription())
            ->setImage(
                $this->managerRegistry->getRepository(Image::class)
                    ->checkDataExistWhithApi($passiveApi->getImage()) ?? $this->createImage($passiveApi->getImage()))
            ;
    }

    private function createStat(StatsRiotApi $statsApi): Stat
    {
        return (new Stat())
            ->setHp($statsApi->getHp())
            ->setHpperlevel($statsApi->getHpperlevel())
            ->setMp($statsApi->getMp())
            ->setMpperlevel($statsApi->getMpperlevel())
            ->setMovespeed($statsApi->getMovespeed())
            ->setArmor($statsApi->getArmor())
            ->setArmorperlevel($statsApi->getArmorperlevel())
            ->setSpellblock($statsApi->getSpellblock())
            ->setSpellblockperlevel($statsApi->getSpellblockperlevel())
            ->setAttackrange($statsApi->getAttackrange())
            ->setHpregen($statsApi->getHpregen())
            ->setHpregenperlevel($statsApi->getHpregenperlevel())
            ->setMpregen($statsApi->getMpregen())
            ->setMpregenperlevel($statsApi->getMpregenperlevel())
            ->setCrit($statsApi->getCrit())
            ->setCritperlevel($statsApi->getCritperlevel())
            ->setAttackdamage($statsApi->getAttackdamage())
            ->setAttackdamageperlevel($statsApi->getAttackdamageperlevel())
            ->setAttackspeed($statsApi->getAttackspeed())
            ->setAttackspeedperlevel($statsApi->getAttackspeedperlevel())
        ;
    }

    private function createTag(string $tagsName): Tag
    {
        $tag = (new Tag())
            ->setName($tagsName)
            ;
        $this->managerRegistry->getManager()->persist($tag);
        $this->managerRegistry->getManager()->flush();
        return $tag;
    }

    private function createSpell(SpellRiotApi $spellApi): Spell
    {
        return (new Spell())
            ->setIdLol($spellApi->getId())
            ->setName($spellApi->getName())
            ->setDescription($spellApi->getDescription())
            ->setTooltip($spellApi->getTooltip())
            ->setCooldownBurn($spellApi->getCooldownBurn())
            ->setCostBurn($spellApi->getCostBurn())
            ->setRange($spellApi->getRange())
            ->setRangeBurn($spellApi->getRangeBurn())
            ->setImage(
                $this->managerRegistry->getRepository(Image::class)
                    ->checkDataExistWhithApi($spellApi->getImage()) ?? $this->createImage($spellApi->getImage()))
            ;
    }
}
