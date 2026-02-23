<?php

declare(strict_types=1);

namespace App\Auth\Presentation\Console;

use App\Auth\Application\Command\CreateAdminUserCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'auth:create-admin',
    description: 'Create an admin user',
)]
final class CreateAdminUserConsoleCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Admin email address', 'admin@lol.local')
            ->addArgument('password', InputArgument::OPTIONAL, 'Admin plain password', 'admin1234');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        try {
            $this->commandBus->dispatch(new CreateAdminUserCommand($email, $password));

            $io->success(sprintf('Admin user "%s" created successfully.', $email));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
