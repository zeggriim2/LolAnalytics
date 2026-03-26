<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Console;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportChallengerSummonersCommand as ImportChallengerSummonersApplicationCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

#[AsCommand(
    name: 'summoner:import:challenger',
    description: 'Enqueue all challenger summoners for async import via RabbitMQ',
)]
final class ImportChallengerSummonersCommand extends Command
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
                'Platform to fetch challenger from (euw1, na1, kr…)',
                'euw1',
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
        $queueValue = (string) $input->getOption('queue');

        $platform = Platform::tryFrom($platformValue);

        if (null === $platform) {
            $io->error(sprintf('Unknown platform "%s". Valid values: %s', $platformValue, implode(', ', array_column(Platform::cases(), 'value'))));

            return Command::FAILURE;
        }

        $queue = Queue::tryFrom($queueValue);

        if (null === $queue) {
            $io->error(sprintf('Unknown queue "%s". Valid values: %s', $queueValue, implode(', ', array_column(Queue::cases(), 'value'))));

            return Command::FAILURE;
        }

        $this->commandBus->dispatch(new ImportChallengerSummonersApplicationCommand($platform, $queue));

        $io->success(sprintf(
            'Challenger summoners for platform "%s" / queue "%s" have been enqueued.',
            $platform->value,
            $queue->value,
        ));

        return Command::SUCCESS;
    }
}
