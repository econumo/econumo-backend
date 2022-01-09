<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\Application\Transaction\Dto\UpdateTransactionV1RequestDto;
use App\Application\Transaction\Dto\UpdateTransactionV1ResultDto;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class UpdateTransactionV1ResultAssembler
{
    private AccountRepositoryInterface $accountRepository;
    private TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler;
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler,
        AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionToDtoV1ResultAssembler = $transactionToDtoV1ResultAssembler;
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
    }

    public function assemble(
        UpdateTransactionV1RequestDto $dto,
        Id $userId,
        Transaction $transaction
    ): UpdateTransactionV1ResultDto {
        $result = new UpdateTransactionV1ResultDto();
        $result->item = $this->transactionToDtoV1ResultAssembler->assemble($transaction);
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        foreach (array_reverse($accounts) as $account) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
