<?php

declare(strict_types=1);

namespace App\Application\Transaction;

use App\Application\Exception\ValidationException;
use App\Application\Transaction\Assembler\RequestToDomainDtoAssembler;
use App\Application\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Application\Transaction\Dto\CreateTransactionV1ResultDto;
use App\Application\Transaction\Assembler\CreateTransactionV1ResultAssembler;
use App\Application\Transaction\Dto\DeleteTransactionV1RequestDto;
use App\Application\Transaction\Dto\DeleteTransactionV1ResultDto;
use App\Application\Transaction\Assembler\DeleteTransactionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\TransactionServiceInterface;

class TransactionService
{
    private CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler;
    private RequestToDomainDtoAssembler $requestToDomainDtoAssembler;
    private TransactionServiceInterface $transactionService;
    private DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler;
    private TransactionRepositoryInterface $transactionRepository;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler,
        RequestToDomainDtoAssembler $requestToDomainDtoAssembler,
        TransactionServiceInterface $transactionService,
        DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler,
        TransactionRepositoryInterface $transactionRepository,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->createTransactionV1ResultAssembler = $createTransactionV1ResultAssembler;
        $this->requestToDomainDtoAssembler = $requestToDomainDtoAssembler;
        $this->transactionService = $transactionService;
        $this->deleteTransactionV1ResultAssembler = $deleteTransactionV1ResultAssembler;
        $this->transactionRepository = $transactionRepository;
        $this->accountAccessService = $accountAccessService;
    }

    public function createTransaction(
        CreateTransactionV1RequestDto $dto,
        Id $userId
    ): CreateTransactionV1ResultDto {
        $accountId = new Id($dto->accountId);
        if (!$this->accountAccessService->canAddTransaction($userId, $accountId)) {
            throw new ValidationException(sprintf('Account %s not available', $dto->accountId));
        }

        $transactionDto = $this->requestToDomainDtoAssembler->assemble($dto, $userId);
        $transaction = $this->transactionService->createTransaction($transactionDto);

        return $this->createTransactionV1ResultAssembler->assemble($dto, $transaction);
    }

    public function deleteTransaction(
        DeleteTransactionV1RequestDto $dto,
        Id $userId
    ): DeleteTransactionV1ResultDto {
        $transaction = $this->transactionRepository->get(new Id($dto->id));
        if (!$this->accountAccessService->canDeleteTransaction($userId, $transaction->getAccountId())) {
            throw new ValidationException(sprintf('Transaction %s not available', $dto->id));
        }

        $this->transactionService->deleteTransaction($transaction);
        return $this->deleteTransactionV1ResultAssembler->assemble($dto, $transaction);
    }
}
