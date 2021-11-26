<?php

declare(strict_types=1);

namespace App\Application\Currency\Assembler;

use App\Application\Currency\Dto\CurrencyResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyIdToDtoV1ResultAssembler
{
    private CurrencyRepositoryInterface $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function assemble(Id $currencyId): CurrencyResultDto
    {
        $currency = $this->currencyRepository->get($currencyId);
        $dto = new CurrencyResultDto();
        $dto->id = $currency->getId()->getValue();
        $dto->alias = $currency->getAlias();
        $dto->sign = $currency->getSign();
        return $dto;
    }
}
