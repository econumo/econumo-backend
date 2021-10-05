<?php

declare(strict_types=1);

namespace App\Application\Account\Account\Assembler;

use App\Application\Account\Account\Dto\UpdateAccountV1RequestDto;
use App\Application\Account\Account\Dto\UpdateAccountV1ResultDto;
use App\Application\Account\Collection\Assembler\AccountToDtoV1ResultAssembler;
use App\Application\Transaction\Collection\Assembler\TransactionToDtoV1ResultAssembler;
use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;

class UpdateAccountV1ResultAssembler
{
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;
    private TransactionToDtoV1ResultAssembler $transactionToDtoV1ResultAssembler;

    public function __construct(
        AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler,
        TransactionToDtoV1ResultAssembler $transactionToDtoV1ResultAssembler
    ) {
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
        $this->transactionToDtoV1ResultAssembler = $transactionToDtoV1ResultAssembler;
    }

    public function assemble(
        UpdateAccountV1RequestDto $dto,
        Account $account,
        ?Transaction $transaction = null
    ): UpdateAccountV1ResultDto {
        $result = new UpdateAccountV1ResultDto();
        $result->item = $this->accountToDtoV1ResultAssembler->assemble($account);
        if ($transaction) {
            $result->transaction = $this->transactionToDtoV1ResultAssembler->assemble($transaction);
        }

        return $result;
    }
}
