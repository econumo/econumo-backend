<?php

declare(strict_types=1);

namespace App\Application\System;

use App\Application\System\Dto\ImportCurrencyListV1RequestDto;
use App\Application\System\Dto\ImportCurrencyListV1ResultDto;
use App\Application\System\Assembler\ImportCurrencyListV1ResultAssembler;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Service\Currency\CurrencyUpdateServiceInterface;
use App\Domain\Service\Dto\CurrencyDto;

class CurrencyListService
{
    public function __construct(private readonly ImportCurrencyListV1ResultAssembler $importCurrencyListV1ResultAssembler, private readonly CurrencyUpdateServiceInterface $currencyUpdateService)
    {
    }

    public function importCurrencyList(
        ImportCurrencyListV1RequestDto $dto
    ): ImportCurrencyListV1ResultDto {
        $currencies = [];
        foreach ($dto->items as $item) {
            $currencyDto = new CurrencyDto();
            $currencyDto->code = new CurrencyCode($item);
            $currencyDto->symbol = '';
            $currencies[] = $currencyDto;
        }
        $this->currencyUpdateService->updateCurrencies($currencies);
        return $this->importCurrencyListV1ResultAssembler->assemble($dto);
    }
}
