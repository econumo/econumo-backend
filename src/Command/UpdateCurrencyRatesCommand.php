<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Service\Currency\CurrencyRatesLoaderServiceInterface;
use App\Domain\Service\Currency\CurrencyRatesUpdateServiceInterface;
use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCurrencyRatesCommand extends Command
{
    protected static $defaultName = 'app:update-currency-rates';
    protected static $defaultDescription = 'Load and update currency rates';
    private CurrencyRatesLoaderServiceInterface $currencyRatesLoaderService;
    private CurrencyRatesUpdateServiceInterface $currencyRatesUpdateService;

    public function __construct(
        CurrencyRatesLoaderServiceInterface $currencyRatesLoaderService,
        CurrencyRatesUpdateServiceInterface $currencyRatesUpdateService
    ) {
        parent::__construct(self::$defaultName);
        $this->currencyRatesLoaderService = $currencyRatesLoaderService;
        $this->currencyRatesUpdateService = $currencyRatesUpdateService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('date', InputArgument::OPTIONAL, 'Date (Y-m-d)', date('Y-m-d'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $input->getArgument('date'));
        $currencyRates = $this->currencyRatesLoaderService->loadCurrencyRates($date);
        $io->info(sprintf('Loaded %d currency rates', count($currencyRates)));
        $updatedCnt = $this->currencyRatesUpdateService->updateCurrencyRates(...$currencyRates);
        $io->success(sprintf('Updated %d currency rates', $updatedCnt));

        return Command::SUCCESS;
    }
}
