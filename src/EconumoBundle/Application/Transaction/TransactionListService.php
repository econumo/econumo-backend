<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction;

use App\EconumoBundle\Application\Transaction\Assembler\GetTransactionListV1ResultAssembler;
use App\EconumoBundle\Application\Transaction\Assembler\ImportTransactionListV1ResultAssembler;
use App\EconumoBundle\Application\Transaction\Dto\GetTransactionListV1RequestDto;
use App\EconumoBundle\Application\Transaction\Dto\GetTransactionListV1ResultDto;
use App\EconumoBundle\Application\Transaction\Dto\ImportTransactionListV1RequestDto;
use App\EconumoBundle\Application\Transaction\Dto\ImportTransactionListV1ResultDto;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoBundle\Domain\Service\AccountAccessServiceInterface;
use App\EconumoBundle\Domain\Service\ImportTransactionServiceInterface;
use App\EconumoBundle\Domain\Service\TransactionServiceInterface;
use DateTimeImmutable;

readonly class TransactionListService
{
    public function __construct(
        private GetTransactionListV1ResultAssembler $getTransactionListV1ResultAssembler,
        private ImportTransactionListV1ResultAssembler $importTransactionListV1ResultAssembler,
        private TransactionRepositoryInterface $transactionRepository,
        private AccountAccessServiceInterface $accountAccessService,
        private TransactionServiceInterface $transactionService,
        private ImportTransactionServiceInterface $importTransactionService
    ) {
    }

    public function importTransactionList(
        ImportTransactionListV1RequestDto $dto,
        Id $userId
    ): ImportTransactionListV1ResultDto {
        if (!$dto->file) {
            $result = new ImportTransactionListV1ResultDto();
            $result->errors[] = 'No file provided';
            return $result;
        }

        $domainResult = $this->importTransactionService->importFromCsv($dto->file, $dto->mapping, $userId);

        return $this->importTransactionListV1ResultAssembler->assemble($domainResult);
    }

    public function getTransactionList(
        GetTransactionListV1RequestDto $dto,
        Id $userId
    ): GetTransactionListV1ResultDto {
        if ($dto->accountId) {
            $this->accountAccessService->checkViewTransactionsAccess($userId, new Id($dto->accountId));
            $transactions = $this->transactionRepository->findByAccountId(new Id($dto->accountId));
        } elseif ($dto->periodStart && $dto->periodEnd) {
            $periodStart = new DateTimeImmutable($dto->periodStart);
            $periodEnd = new DateTimeImmutable($dto->periodEnd);
            $transactions = $this->transactionService->getTransactionsForVisibleAccounts(
                $userId,
                $periodStart,
                $periodEnd
            );
        } else {
            $transactions = $this->transactionService->getTransactionsForVisibleAccounts($userId);
        }

        return $this->getTransactionListV1ResultAssembler->assemble($dto, $userId, $transactions);
    }
}
