<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\System;

use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyListV1RequestDto;
use App\EconumoOneBundle\Application\System\Dto\ImportCurrencyListV1ResultDto;
use App\EconumoOneBundle\Application\System\Assembler\ImportCurrencyListV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Service\Currency\CurrencyUpdateServiceInterface;
use App\EconumoOneBundle\Domain\Service\Dto\CurrencyDto;

readonly class CurrencyListService
{
    public function __construct(private ImportCurrencyListV1ResultAssembler $importCurrencyListV1ResultAssembler, private CurrencyUpdateServiceInterface $currencyUpdateService)
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
