<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency\Assembler;

use App\EconumoOneBundle\Application\Currency\Dto\CurrencyResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;

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
