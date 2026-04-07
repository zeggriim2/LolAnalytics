<?php

declare(strict_types=1);

namespace App\League\Presentation\Console;

use App\League\Application\Command\RefreshLeagueCommand as RefreshLeagueApplicationCommand;
use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

#[AsCommand(
    name: 'league:refresh',
    description: 'Fetch and store Challenger / Grandmaster / Master league entries from Riot API',
)]
final class RefreshLeagueCommand extends Command
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'platform',
                'p',
                InputOption::VALUE_REQUIRED,
                'Platform to fetch from (euw1, na1, kr…)',
                'euw1',
            )
            ->addOption(
                'tier',
                't',
                InputOption::VALUE_REQUIRED,
                sprintf('Tier: %s', implode(', ', array_column(LeagueTier::cases(), 'value'))),
                LeagueTier::CHALLENGER->value,
            )
            ->addOption(
                'queue',
                'qu',
                InputOption::VALUE_REQUIRED,
                'Queue type: RANKED_SOLO_5x5, RANKED_FLEX_SR',
                Queue::RANKED_SOLO->value,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $platformValue = strtolower((string) $input->getOption('platform'));
        $tierValue = strtolower((string) $input->getOption('tier'));
        $queueValue = (string) $input->getOption('queue');

        $platform = Platform::tryFrom($platformValue);

        if (null === $platform) {
            $io->error(sprintf('Unknown platform "%s". Valid: %s', $platformValue, implode(', ', array_column(Platform::cases(), 'value'))));

            return Command::FAILURE;
        }

        $tier = LeagueTier::tryFrom($tierValue);

        if (null === $tier) {
            $io->error(sprintf('Unknown tier "%s". Valid: %s', $tierValue, implode(', ', array_column(LeagueTier::cases(), 'value'))));

            return Command::FAILURE;
        }

        $queue = Queue::tryFrom($queueValue);

        if (null === $queue) {
            $io->error(sprintf('Unknown queue "%s".', $queueValue));

            return Command::FAILURE;
        }

        $this->commandBus->dispatch(new RefreshLeagueApplicationCommand($platform, $tier, $queue));

        $io->success(sprintf(
            '%s league for platform "%s" / queue "%s" has been refreshed.',
            ucfirst($tier->value),
            $platform->value,
            $queue->value,
        ));

        return Command::SUCCESS;
    }
}
