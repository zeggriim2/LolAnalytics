<?php

namespace App\Command\DataDragonCommand;

use App\Entity\Language;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;

#[AsCommand(
    name: 'app:languages',
    description: 'Ajoute les Languages en Bdd.',
)]
final class LanguagesCommand extends Command
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
        $io->progressStart();

        $languages = $this->dataDragonApi->getLanguages();
        $countLanguageAdd = 0;
        foreach ($languages as $language) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(Language::class)
                ->findOneBy(['code' => $language])) {
                $countLanguageAdd++;
                $languageeEntity = (new Language())
                    ->setCode($language)
                ;

                $this->managerRegistry->getManager()->persist($languageeEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les Languages on été ajoutées ($countLanguageAdd)");

        return Command::SUCCESS;
    }
}
