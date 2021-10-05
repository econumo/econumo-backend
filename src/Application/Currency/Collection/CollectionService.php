<?php

declare(strict_types=1);

namespace App\Application\Currency\Collection;

use App\Application\Currency\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Currency\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Currency\Collection\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRepositoryInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private CurrencyRepositoryInterface $currencyRepository;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        CurrencyRepositoryInterface $currencyRepository
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->currencyRepository = $currencyRepository;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        $currencies = $this->currencyRepository->getAll();
        return $this->getCollectionV1ResultAssembler->assemble($dto, $currencies);
    }
}
