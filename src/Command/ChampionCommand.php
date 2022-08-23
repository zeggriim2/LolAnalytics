<?php

namespace App\Command;

use App\Entity\Champion;
use App\Entity\Image;
use App\Entity\InfoChampion;
use App\Entity\Language;
use App\Entity\Version;
use App\Services\API\LOL\DataDragon\DataDragonApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:champions',
    description: 'Ajout des Champions en BDD',
)]
class ChampionCommand extends Command
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
        $champions = $this->dataDragonApi->getChampions();

        /** @var Version|null $version */
        $version = $this->doctrine->getRepository(Version::class)->findOneBy(['name'=> $champions['version']]);

        // Si la version n'est pas trouvé en BDD
        if($version === null) {
            $io->error("La version n'existe pas en Bdd");
            return Command::FAILURE;
        }

        $count = 0;
        foreach ($champions["data"] as $champion) {
            $count++;
            $this->createChampion($champion, $version);
        }

        $this->doctrine->flush();

        if (0 === $count) {
            $phrase = "Aucun élément de champion n'a été ajouté.";
        } else {
            $phrase = "$count champion ont été ajouté en bdd.";
        }

        $io->success($phrase);

        return Command::SUCCESS;
    }


    private function createChampion(array $champion,Version $version)
    {
        $champion = (new Champion())
            ->setIdName($champion['id'])
            ->setName($champion['name'])
            ->setKey($champion['key'])
            ->setTitle($champion['title'])
            ->setPartype($champion['partype'])
            ->setVersion($version)
        ;

        $champion->setInfoChampion($this->createInfoChampion($champion['info']));
        $champion->setImage($this->createImageChampion($champion['image']));

        $this->doctrine->persist($champion);
    }

    /**
     * @param array $info
     * @return InfoChampion
     */
    private function createInfoChampion(array $info): InfoChampion
    {
        return (new InfoChampion())
            ->setAttack($info['attack'])
            ->setDefense($info['defense'])
            ->setMagic($info['magic'])
            ->setDifficulty($info['difficulty'])
        ;
    }

    /**
     * @param array $image
     * @return Image
     */
    private function createImageChampion(array $image): Image
    {
        return (new Image())
            ->setComplete($image['full'])
            ->setSprite($image['sprite'])
            ->setGroupe($image['full'])
            ->setComplete($image['full'])
            ->setH($image['h'])
            ->setW($image['w'])
            ->setX($image['x'])
            ->setY($image['y'])
        ;
    }
}
