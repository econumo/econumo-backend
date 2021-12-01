<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Transaction\Assembler\TransactionToDtoResultAssembler;
use App\Application\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Application\Transaction\Dto\CreateTransactionV1ResultDto;
use App\Domain\Entity\Transaction;
use App\Domain\Repository\AccountRepositoryInterface;

class CreateTransactionV1ResultAssembler
{
    private AccountRepositoryInterface $accountRepository;
    private TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionToDtoV1ResultAssembler = $transactionToDtoV1ResultAssembler;
    }

    public function assemble(
        CreateTransactionV1RequestDto $dto,
        Transaction $transaction
    ): CreateTransactionV1ResultDto {
        $result = new CreateTransactionV1ResultDto();

        $account = $this->accountRepository->get($transaction->getAccountId());
        $result->accountBalance = $account->getBalance();
        if ($transaction->getAccountRecipientId()) {
            $accountRecipient = $this->accountRepository->get($transaction->getAccountRecipientId());
            $result->accountRecipientBalance = $accountRecipient->getBalance();
        }
        $result->item = $this->transactionToDtoV1ResultAssembler->assemble($transaction);

        return $result;
    }
}
