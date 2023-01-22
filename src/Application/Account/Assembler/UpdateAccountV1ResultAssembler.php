<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\UpdateAccountV1RequestDto;
use App\Application\Account\Dto\UpdateAccountV1ResultDto;
use App\Application\Transaction\Assembler\TransactionToDtoResultAssembler;
use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;

class UpdateAccountV1ResultAssembler
{
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    private TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler;

    public function __construct(
        AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler,
        TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler
    ) {
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
        $this->transactionToDtoV1ResultAssembler = $transactionToDtoV1ResultAssembler;
    }

    public function assemble(
        UpdateAccountV1RequestDto $dto,
        Id $userId,
        Account $account,
        ?Transaction $transaction = null
    ): UpdateAccountV1ResultDto {
        $result = new UpdateAccountV1ResultDto();
        $result->item = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        if ($transaction !== null) {
            $result->transaction = $this->transactionToDtoV1ResultAssembler->assemble($userId, $transaction);
        }

        return $result;
    }
}
