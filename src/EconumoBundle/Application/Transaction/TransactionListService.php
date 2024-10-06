<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction;

use App\EconumoBundle\Application\Transaction\Dto\GetTransactionListV1RequestDto;
use App\EconumoBundle\Application\Transaction\Dto\GetTransactionListV1ResultDto;
use App\EconumoBundle\Application\Transaction\Assembler\GetTransactionListV1ResultAssembler;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoBundle\Domain\Service\AccountAccessServiceInterface;
use App\EconumoBundle\Domain\Service\TransactionServiceInterface;
use DateTimeImmutable;

class TransactionListService
{
    public function __construct(private readonly GetTransactionListV1ResultAssembler $getTransactionListV1ResultAssembler, private readonly TransactionRepositoryInterface $transactionRepository, private readonly AccountAccessServiceInterface $accountAccessService, private readonly TransactionServiceInterface $transactionService)
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
            if ($dto->periodStart && $dto->periodEnd) {
                $periodStart = new DateTimeImmutable($dto->periodStart);
                $periodEnd = new DateTimeImmutable($dto->periodEnd);
                $transactions = $this->transactionService->getTransactionsForVisibleAccounts($userId, $periodStart, $periodEnd);
            } else {
                $transactions = $this->transactionService->getTransactionsForVisibleAccounts($userId);
            }
        }

        return $this->getTransactionListV1ResultAssembler->assemble($dto, $userId, $transactions);
    }
}
