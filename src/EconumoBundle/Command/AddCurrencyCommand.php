<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Service\Currency\CurrencyUpdateServiceInterface;
use App\EconumoBundle\Domain\Service\Dto\CurrencyDto;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCurrencyCommand extends Command
{
    protected static $defaultName = 'app:add-currency';

    protected static $defaultDescription = 'Add a new currency';

    public function __construct(
        private readonly CurrencyUpdateServiceInterface $currencyUpdateService,
        private readonly CurrencyRepositoryInterface $currencyRepository
    ) {
        parent::__construct(self::$defaultName);
    }


    protected function configure(): void
    {
        $this
            ->addArgument(
                'currency-code',
                InputArgument::REQUIRED,
                '3 digit currency code ISO4217 (USD, EUR, check https://en.wikipedia.org/wiki/ISO_4217)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $code = trim((string)$input->getArgument('currency-code'));

        $currencies = [];
        $currencyDto = new CurrencyDto();
        $currencyDto->code = new CurrencyCode($code);
        $currencyDto->symbol = '';
        $currencies[] = $currencyDto;
        $this->currencyUpdateService->updateCurrencies($currencies);
        $currency = $this->currencyRepository->getByCode($currencyDto->code);

        if (!$currency instanceof Currency) {
            $io->error(sprintf("Currency %s wasn't added!", $code));
            return Command::FAILURE;
        }

        $io->success(
            sprintf(
                'Currency %s (%s, %s) successfully created! (id: %s)',
                $code,
                $currency->getName(),
                $currency->getSymbol(),
                $currency->getId()->getValue()
            )
        );
        return Command::SUCCESS;
    }
}
