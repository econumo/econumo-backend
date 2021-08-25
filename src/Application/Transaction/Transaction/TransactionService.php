<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction;

use App\Application\Exception\ValidationException;
use App\Application\Transaction\Transaction\Assembler\RequestToDomainDtoAssembler;
use App\Application\Transaction\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Application\Transaction\Transaction\Dto\CreateTransactionV1ResultDto;
use App\Application\Transaction\Transaction\Assembler\CreateTransactionV1ResultAssembler;
use App\Application\Transaction\Transaction\Dto\DeleteTransactionV1RequestDto;
use App\Application\Transaction\Transaction\Dto\DeleteTransactionV1ResultDto;
use App\Application\Transaction\Transaction\Assembler\DeleteTransactionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\TransactionServiceInterface;

class TransactionService
{
    private CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler;
    private AccountServiceInterface $accountService;
    private RequestToDomainDtoAssembler $requestToDomainDtoAssembler;
    private TransactionServiceInterface $transactionService;
    private DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler,
        AccountServiceInterface $accountService,
        RequestToDomainDtoAssembler $requestToDomainDtoAssembler,
        TransactionServiceInterface $transactionService,
        DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->createTransactionV1ResultAssembler = $createTransactionV1ResultAssembler;
        $this->accountService = $accountService;
        $this->requestToDomainDtoAssembler = $requestToDomainDtoAssembler;
        $this->transactionService = $transactionService;
        $this->deleteTransactionV1ResultAssembler = $deleteTransactionV1ResultAssembler;
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransaction(
        CreateTransactionV1RequestDto $dto,
        Id $userId
    ): CreateTransactionV1ResultDto {
        $accountId = new Id($dto->accountId);
        if (!$this->accountService->isAccountAvailable($userId, $accountId)) {
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
        if (!$this->accountService->isAccountAvailable($userId, $transaction->getAccountId())) {
            throw new ValidationException(sprintf('Transaction %s not available', $dto->id));
        }

        $this->transactionService->deleteTransaction($transaction);
        return $this->deleteTransactionV1ResultAssembler->assemble($dto, $transaction);
    }
}