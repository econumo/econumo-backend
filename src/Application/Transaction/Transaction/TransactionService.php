<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction;

use App\Application\Exception\ValidationException;
use App\Application\Transaction\Transaction\Assembler\RequestToDomainDtoAssembler;
use App\Application\Transaction\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Application\Transaction\Transaction\Dto\CreateTransactionV1ResultDto;
use App\Application\Transaction\Transaction\Assembler\CreateTransactionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\TransactionServiceInterface;

class TransactionService
{
    private CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler;
    private AccountServiceInterface $accountService;
    private RequestToDomainDtoAssembler $requestToDomainDtoAssembler;
    private TransactionServiceInterface $transactionService;

    public function __construct(
        CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler,
        AccountServiceInterface $accountService,
        RequestToDomainDtoAssembler $requestToDomainDtoAssembler,
        TransactionServiceInterface $transactionService
    ) {
        $this->createTransactionV1ResultAssembler = $createTransactionV1ResultAssembler;
        $this->accountService = $accountService;
        $this->requestToDomainDtoAssembler = $requestToDomainDtoAssembler;
        $this->transactionService = $transactionService;
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
}
