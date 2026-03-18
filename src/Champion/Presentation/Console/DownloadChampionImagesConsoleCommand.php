<?php

declare(strict_types=1);

namespace App\Champion\Presentation\Console;

use App\Champion\Application\Port\ChampionAssetDownloaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:champions:download-images',
    description: 'Download champion images from DataDragon CDN to local storage',
)]
final class DownloadChampionImagesConsoleCommand extends Command
{
    public function __construct(
        private readonly ChampionAssetDownloaderInterface $downloader,
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addArgument('version', InputArgument::REQUIRED, 'Game version (e.g. 15.1.1)');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $version = $input->getArgument('version');

        $io->info(sprintf('Fetching champion list for version %s...', $version));

        try {
            $filenames = $this->downloader->getImageFilenames($version);

            $progressBar = $io->createProgressBar(count($filenames));
            $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');

            $downloaded = 0;

            foreach ($this->downloader->downloadAll($version, $filenames) as $filename => $wasDownloaded) {
                $progressBar->setMessage($filename);

                if ($wasDownloaded) {
                    ++$downloaded;
                }
                $progressBar->advance();
            }

            $progressBar->finish();
            $io->newLine(2);
            $io->success(sprintf(
                '%d image(s) downloaded to public/images/champions/%s/ (%d already present)',
                $downloaded,
                $version,
                count($filenames) - $downloaded,
            ));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->newLine();
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
