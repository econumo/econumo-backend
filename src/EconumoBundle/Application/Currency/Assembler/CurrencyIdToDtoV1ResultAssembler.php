<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Currency\Assembler;

use App\EconumoBundle\Application\Currency\Dto\CurrencyResultDto;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyIdToDtoV1ResultAssembler
{
    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository)
    {
    }

    public function assemble(Id $currencyId): CurrencyResultDto
    {
        $currency = $this->currencyRepository->get($currencyId);
        $dto = new CurrencyResultDto();
        $dto->id = $currency->getId()->getValue();
        $dto->code = $currency->getCode()->getValue();
        $dto->name = $currency->getName();
        $dto->symbol = $currency->getSymbol();
        return $dto;
    }
}
