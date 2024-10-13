<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Transaction;

use App\EconumoOneBundle\Application\Transaction\Assembler\UpdateTransactionRequestToDomainDtoAssembler;
use App\EconumoOneBundle\Application\Transaction\Dto\UpdateTransactionV1RequestDto;
use App\EconumoOneBundle\Application\Transaction\Dto\UpdateTransactionV1ResultDto;
use App\EconumoOneBundle\Application\Transaction\Assembler\UpdateTransactionV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Application\Transaction\Assembler\RequestToDomainDtoAssembler;
use App\EconumoOneBundle\Application\Transaction\Dto\CreateTransactionV1RequestDto;
use App\EconumoOneBundle\Application\Transaction\Dto\CreateTransactionV1ResultDto;
use App\EconumoOneBundle\Application\Transaction\Assembler\CreateTransactionV1ResultAssembler;
use App\EconumoOneBundle\Application\Transaction\Dto\DeleteTransactionV1RequestDto;
use App\EconumoOneBundle\Application\Transaction\Dto\DeleteTransactionV1ResultDto;
use App\EconumoOneBundle\Application\Transaction\Assembler\DeleteTransactionV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AccountAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\TransactionServiceInterface;
use App\EconumoOneBundle\Domain\Service\Translation\TranslationServiceInterface;

class TransactionService
{
    public function __construct(private readonly CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler, private readonly RequestToDomainDtoAssembler $requestToDomainDtoAssembler, private readonly TransactionServiceInterface $transactionService, private readonly DeleteTransactionV1ResultAssembler $deleteTransactionV1ResultAssembler, private readonly TransactionRepositoryInterface $transactionRepository, private readonly AccountAccessServiceInterface $accountAccessService, private readonly UpdateTransactionV1ResultAssembler $updateTransactionV1ResultAssembler, private readonly UpdateTransactionRequestToDomainDtoAssembler $updateTransactionRequestToDomainDtoAssembler, private readonly TranslationServiceInterface $translationService)
    {
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
