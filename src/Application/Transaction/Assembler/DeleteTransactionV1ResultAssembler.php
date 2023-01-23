<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\Application\Transaction\Dto\DeleteTransactionV1RequestDto;
use App\Application\Transaction\Dto\DeleteTransactionV1ResultDto;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class DeleteTransactionV1ResultAssembler
{
    public function __construct(private readonly AccountRepositoryInterface $accountRepository, private readonly TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler, private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        DeleteTransactionV1RequestDto $dto,
        Id $userId,
        Transaction $transaction
    ): DeleteTransactionV1ResultDto {
        $result = new DeleteTransactionV1ResultDto();
        $result->item = $this->transactionToDtoV1ResultAssembler->assemble($userId, $transaction);
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        foreach (array_reverse($accounts) as $account) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
