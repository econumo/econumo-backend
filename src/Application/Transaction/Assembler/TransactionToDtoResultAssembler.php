<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Transaction\Dto\TransactionResultDto;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;

class TransactionToDtoResultAssembler
{
    private UserIdToDtoResultAssembler $userIdToDtoResultAssembler;

    public function __construct(
        UserIdToDtoResultAssembler $userIdToDtoResultAssembler
    ) {
        $this->userIdToDtoResultAssembler = $userIdToDtoResultAssembler;
    }

    public function assemble(
        Id $userId,
        Transaction $transaction
    ): TransactionResultDto {
        $item = new TransactionResultDto();
        $item->id = $transaction->getId()->getValue();
        $item->author = $this->userIdToDtoResultAssembler->assemble($transaction->getUserId());
        $item->type = $transaction->getType()->getAlias();
        $item->accountId = $transaction->getAccountId()->getValue();
        $item->accountRecipientId = $transaction->getAccountRecipientId(
        ) === null ? null : $transaction->getAccountRecipientId()->getValue();
        $item->amount = $transaction->getAmount();
        $item->amountRecipient = $transaction->getAmountRecipient() ?? $transaction->getAmount();
        $item->categoryId = $transaction->getCategoryId() === null ? null : $transaction->getCategoryId()->getValue();
        $item->description = $transaction->getDescription();
        $item->payeeId = $transaction->getPayeeId() === null ? null : $transaction->getPayeeId()->getValue();
        $item->tagId = $transaction->getTagId() === null ? null : $transaction->getTagId()->getValue();
        $item->date = $transaction->getSpentAt()->format('Y-m-d H:i:s');

        return $item;
    }
}
