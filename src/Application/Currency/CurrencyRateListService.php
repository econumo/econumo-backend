<?php

declare(strict_types=1);

namespace App\Application\Currency;

use App\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\Application\Currency\Dto\GetCurrencyRateListV1ResultDto;
use App\Application\Currency\Assembler\GetCurrencyRateListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Currency\CurrencyRateServiceInterface;

class CurrencyRateListService
{
    private GetCurrencyRateListV1ResultAssembler $getCurrencyRateListV1ResultAssembler;
    private CurrencyRateServiceInterface $currencyRateService;

    public function __construct(
        GetCurrencyRateListV1ResultAssembler $getCurrencyRateListV1ResultAssembler,
        CurrencyRateServiceInterface $currencyRateService
    ) {
        $this->getCurrencyRateListV1ResultAssembler = $getCurrencyRateListV1ResultAssembler;
        $this->currencyRateService = $currencyRateService;
    }

    public function getCurrencyRateList(
        GetCurrencyRateListV1RequestDto $dto,
        Id $userId
    ): GetCurrencyRateListV1ResultDto {
        $rates = $this->currencyRateService->getLatestCurrencyRates();
        return $this->getCurrencyRateListV1ResultAssembler->assemble($dto, $rates);
    }
}
