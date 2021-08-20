<?php

declare(strict_types=1);

namespace App\Application\Payee\Collection;

use App\Application\Payee\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Payee\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Payee\Collection\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PayeeRepositoryInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private PayeeRepositoryInterface $payeeRepository;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        PayeeRepositoryInterface $payeeRepository
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->payeeRepository = $payeeRepository;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        $payees = $this->payeeRepository->findByUserId($userId);
        return $this->getCollectionV1ResultAssembler->assemble($dto, $payees);
    }
}
