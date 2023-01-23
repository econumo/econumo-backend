<?php

declare(strict_types=1);

namespace App\Application\Transaction;

use App\Application\Transaction\Dto\GetTransactionListV1RequestDto;
use App\Application\Transaction\Dto\GetTransactionListV1ResultDto;
use App\Application\Transaction\Assembler\GetTransactionListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;

class TransactionListService
{
    public function __construct(private readonly GetTransactionListV1ResultAssembler $getTransactionListV1ResultAssembler, private readonly TransactionRepositoryInterface $transactionRepository, private readonly AccountAccessServiceInterface $accountAccessService)
    {
    }

    public function getTransactionList(
        GetTransactionListV1RequestDto $dto,
        Id $userId
    ): GetTransactionListV1ResultDto {
        if ($dto->accountId) {
            $this->accountAccessService->checkViewTransactionsAccess($userId, new Id($dto->accountId));
            $transactions = $this->transactionRepository->findByAccountId(new Id($dto->accountId));
        } else {
            $transactions = $this->transactionRepository->findAvailableForUserId($userId);
        }

        return $this->getTransactionListV1ResultAssembler->assemble($dto, $userId, $transactions);
    }
}
