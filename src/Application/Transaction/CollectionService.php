<?php

declare(strict_types=1);

namespace App\Application\Transaction;

use App\Application\Exception\ValidationException;
use App\Application\Transaction\Dto\GetCollectionV1RequestDto;
use App\Application\Transaction\Dto\GetCollectionV1ResultDto;
use App\Application\Transaction\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private TransactionRepositoryInterface $transactionRepository;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        TransactionRepositoryInterface $transactionRepository,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->transactionRepository = $transactionRepository;
        $this->accountAccessService = $accountAccessService;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        if ($dto->accountId) {
            $this->accountAccessService->checkViewTransactionsAccess($userId, new Id($dto->accountId));
            $transactions = $this->transactionRepository->findByAccountId(new Id($dto->accountId));
        } else {
            $transactions = $this->transactionRepository->findByUserId($userId);
        }
        return $this->getCollectionV1ResultAssembler->assemble($dto, $transactions);
    }
}
