<?php

declare(strict_types=1);

namespace App\Presentation\Console;

use App\Application\Version\UseCase\FetchVersionsUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:riot:fetch-versions',
    description: 'Récupére les versions depuis l\'API Riot'
)]
final class FetchVersionsCommand extends Command
{

    public function __construct(
        private readonly FetchVersionsUseCase $fetchVersionsUseCase,
    )
    {
        parent::__construct();
    }

    public function configure()
    {
        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_NONE,
            'Force la mise à jour en supprimant les versions existantes'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption('force');

        $io->title('🎮 Récupération des versions Riot API');

        if ($force) {
            $io->warning('Mode force activé - toutes les versions existantes seront supprimées');
        }

        try {
            $requestId = $this->fetchVersionsUseCase->execute($force);

            $io->success([
                'Tâche de récupération des versions ajoutée à la queue !',
                "Request ID: $requestId"
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Erreur: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
