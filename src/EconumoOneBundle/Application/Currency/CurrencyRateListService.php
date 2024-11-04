<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency;

use App\EconumoOneBundle\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\EconumoOneBundle\Application\Currency\Dto\GetCurrencyRateListV1ResultDto;
use App\EconumoOneBundle\Application\Currency\Assembler\GetCurrencyRateListV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Currency\CurrencyRateServiceInterface;

class CurrencyRateListService
{
    public function __construct(private readonly GetCurrencyRateListV1ResultAssembler $getCurrencyRateListV1ResultAssembler, private readonly CurrencyRateServiceInterface $currencyRateService)
    {
    }

    public function getCurrencyRateList(
        GetCurrencyRateListV1RequestDto $dto,
        Id $userId
    ): GetCurrencyRateListV1ResultDto {
        $rates = $this->currencyRateService->getLatestCurrencyRates();
        return $this->getCurrencyRateListV1ResultAssembler->assemble($dto, $rates);
    }
}
