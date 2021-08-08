<?php

declare(strict_types=1);

namespace App\Application\Account\Collection;

use App\Application\Account\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Account\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Account\Collection\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->accountRepository = $accountRepository;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        $accounts = $this->accountRepository->findByUserId($userId);
        return $this->getCollectionV1ResultAssembler->assemble($dto, $accounts);
    }
}
