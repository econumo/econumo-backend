<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Service\Currency\CurrencyLoaderServiceInterface;
use App\Domain\Service\Currency\CurrencyUpdateServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCurrenciesCommand extends Command
{
    protected static $defaultName = 'app:update-currencies';

    protected static $defaultDescription = 'Load and update currencies';

    public function __construct(
        private readonly CurrencyLoaderServiceInterface $currencyLoaderService,
        private readonly CurrencyUpdateServiceInterface $currencyUpdateService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filter', InputArgument::IS_ARRAY, 'List of currencies (USD, EUR, etc)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $currencies = $this->currencyLoaderService->loadCurrencies();
        $filter = $input->getArgument('filter');
        if (is_array($filter) && $filter !== []) {
            $filtered = [];
            foreach ($currencies as $currency) {
                if (in_array($currency->code->getValue(), $filter, true)) {
                    $filtered[] = $currency;
                }
            }
        } else {
            $filtered = $currencies;
        }

        $io->info(sprintf('Loaded %d currencies', count($filtered)));
        $this->currencyUpdateService->updateCurrencies($filtered);
        $io->success('Currencies updated');

        return Command::SUCCESS;
    }
}
