<?php

declare(strict_types=1);

namespace App\Application\Transaction;

use App\Application\Transaction\Assembler\UpdateTransactionRequestToDomainDtoAssembler;
use App\Application\Transaction\Dto\UpdateTransactionV1RequestDto;
use App\Application\Transaction\Dto\UpdateTransactionV1ResultDto;
use App\Application\Transaction\Assembler\UpdateTransactionV1ResultAssembler;
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
use App\Domain\Service\Translation\TranslationServiceInterface;

class TransactionService
{
    private CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler;
    private RequestToDomainDtoAssembler $requestToDomainDtoAssembler;
    private TransactionServiceInterface $transactionService;
    private DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler;
    private TransactionRepositoryInterface $transactionRepository;
    private AccountAccessServiceInterface $accountAccessService;
    private UpdateTransactionV1ResultAssembler $updateTransactionV1ResultAssembler;
    private UpdateTransactionRequestToDomainDtoAssembler $updateTransactionRequestToDomainDtoAssembler;
    private TranslationServiceInterface $translationService;

    public function __construct(
        CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler,
        RequestToDomainDtoAssembler $requestToDomainDtoAssembler,
        TransactionServiceInterface $transactionService,
        DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler,
        TransactionRepositoryInterface $transactionRepository,
        AccountAccessServiceInterface $accountAccessService,
        UpdateTransactionV1ResultAssembler $updateTransactionV1ResultAssembler,
        UpdateTransactionRequestToDomainDtoAssembler $updateTransactionRequestToDomainDtoAssembler,
        TranslationServiceInterface $translationService
    ) {
        $this->createTransactionV1ResultAssembler = $createTransactionV1ResultAssembler;
        $this->requestToDomainDtoAssembler = $requestToDomainDtoAssembler;
        $this->transactionService = $transactionService;
        $this->deleteTransactionV1ResultAssembler = $deleteTransactionV1ResultAssembler;
        $this->transactionRepository = $transactionRepository;
        $this->accountAccessService = $accountAccessService;
        $this->updateTransactionV1ResultAssembler = $updateTransactionV1ResultAssembler;
        $this->updateTransactionRequestToDomainDtoAssembler = $updateTransactionRequestToDomainDtoAssembler;
        $this->translationService = $translationService;
    }

    public function createTransaction(
        CreateTransactionV1RequestDto $dto,
        Id $userId
    ): CreateTransactionV1ResultDto {
        $accountId = new Id($dto->accountId);
        if (!$this->accountAccessService->canAddTransaction($userId, $accountId)) {
            throw new ValidationException($this->translationService->trans('account.account.not_available', ['id' => $dto->accountId]));
        }

        $transactionDto = $this->requestToDomainDtoAssembler->assemble($dto, $userId);
        $transaction = $this->transactionService->createTransaction($transactionDto);

        return $this->createTransactionV1ResultAssembler->assemble($dto, $userId, $transaction);
    }

    public function deleteTransaction(
        DeleteTransactionV1RequestDto $dto,
        Id $userId
    ): DeleteTransactionV1ResultDto {
        $transaction = $this->transactionRepository->get(new Id($dto->id));
        if (!$this->accountAccessService->canDeleteTransaction($userId, $transaction->getAccountId())) {
            throw new ValidationException($this->translationService->trans('transaction.transaction.not_available', ['id' => $dto->id]));
        }

        $this->transactionService->deleteTransaction($transaction);
        return $this->deleteTransactionV1ResultAssembler->assemble($dto, $userId, $transaction);
    }

    public function updateTransaction(
        UpdateTransactionV1RequestDto $dto,
        Id $userId
    ): UpdateTransactionV1ResultDto {
        $accountId = new Id($dto->accountId);
        if (!$this->accountAccessService->canUpdateTransaction($userId, $accountId)) {
            throw new ValidationException($this->translationService->trans('account.account.not_available', ['id' => $dto->accountId]));
        }

        $transactionDto = $this->updateTransactionRequestToDomainDtoAssembler->assemble($dto, $userId);
        $transaction = $this->transactionService->updateTransaction(new Id($dto->id), $transactionDto);

        return $this->updateTransactionV1ResultAssembler->assemble($dto, $userId, $transaction);
    }
}
