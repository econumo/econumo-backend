<?php

declare(strict_types=1);

namespace App\Application\Transaction\Collection;

use App\Application\Exception\ValidationException;
use App\Application\Transaction\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Transaction\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Transaction\Collection\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\AccountServiceInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private AccountServiceInterface $accountService;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        AccountServiceInterface $accountService,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->accountService = $accountService;
        $this->transactionRepository = $transactionRepository;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        if ($dto->accountId && !$this->accountService->isAccountAvailable($userId, new Id($dto->accountId))) {
            throw new ValidationException(sprintf('Account %s not available', $dto->accountId));
        }

        if ($dto->accountId) {
            $transactions = $this->transactionRepository->findByAccountId(new Id($dto->accountId));
        } else {
            $transactions = $this->transactionRepository->findByUserId($userId);
        }
        return $this->getCollectionV1ResultAssembler->assemble($dto, $transactions);
    }
}
