<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Factory\UserFactoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\Currency\CurrencyLoaderServiceInterface;
use App\Domain\Service\Currency\CurrencyUpdateServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCurrenciesCommand extends Command
{
    protected static $defaultName = 'app:update-currencies';
    protected static $defaultDescription = 'Load and update currencies';

    private CurrencyLoaderServiceInterface $currencyLoaderService;
    private CurrencyUpdateServiceInterface $currencyUpdateService;

    public function __construct(
        CurrencyLoaderServiceInterface $currencyLoaderService,
        CurrencyUpdateServiceInterface $currencyUpdateService
    ) {
        parent::__construct(self::$defaultName);
        $this->currencyLoaderService = $currencyLoaderService;
        $this->currencyUpdateService = $currencyUpdateService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $currencies = $this->currencyLoaderService->loadCurrencies();
        $io->info(sprintf('Loaded %d currencies', count($currencies)));
        $this->currencyUpdateService->updateCurrencies(...$currencies);
        $io->success('Currencies updated');

        return Command::SUCCESS;
    }
}
