<?php

namespace App\Command;

use App\Entity\Language;
use App\Services\API\LOL\DataDragon\DataDragonApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:languages',
    description: 'Ajout des Languages en BDD',
)]
class LanguageCommand extends Command
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
        $languages = $this->dataDragonApi->getLanguages();
        $io->progressStart();

        $count = 0;
        if (null !== $languages) {
            foreach ($languages as $language) {
                $io->progressAdvance();
                $languageRepo = $this->doctrine->getRepository(Language::class)->findOneBy(['code' => $language]);
                if (null === $languageRepo) {
                    $languageEntity = (new Language())
                        ->setCode($language)
                    ;
                    $this->doctrine->persist($languageEntity);
                    ++$count;
                }
            }
        }
        $this->doctrine->flush();

        if (0 === $count) {
            $phrase = "Aucun élément de language n'a été ajouté.";
        } else {
            $phrase = "$count language ont été ajouté en bdd.";
        }

        $io->success($phrase);
        $io->progressFinish();

        return Command::SUCCESS;
    }
}
