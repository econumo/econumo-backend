<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\System;

use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyRatesV1RequestDto;
use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyRatesV1ResultDto;
use App\EconumoOneBundle\Application\System\Assembler\ImportCurrencyRatesV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Service\Currency\CurrencyRatesUpdateServiceInterface;
use App\EconumoOneBundle\Domain\Service\Dto\CurrencyRateDto;
use DateTimeImmutable;

readonly class CurrencyRatesService
{
    public function __construct(private ImportCurrencyRatesV1ResultAssembler $importCurrencyRatesV1ResultAssembler, private CurrencyRatesUpdateServiceInterface $currencyRatesUpdateService)
    {
    }

    public function importCurrencyRates(
        ImportCurrencyRatesV1RequestDto $dto
    ): ImportCurrencyRatesV1ResultDto {
        $currencyRatesDate = DateTimeImmutable::createFromFormat('U', $dto->timestamp);
        $currencyBase = new CurrencyCode($dto->base);
        $rates = [];
        foreach ($dto->items as $currencyRate) {
            $item = new CurrencyRateDto();
            $item->code = new CurrencyCode($currencyRate->code);
            $item->rate = $currencyRate->rate;
            $item->date = $currencyRatesDate;
            $item->base = $currencyBase;
            $rates[] = $item;
        }
        $this->currencyRatesUpdateService->updateCurrencyRates($rates);
        return $this->importCurrencyRatesV1ResultAssembler->assemble($dto);
    }
}
