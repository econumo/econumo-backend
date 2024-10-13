<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Transaction\Assembler;

use App\EconumoOneBundle\Application\Transaction\Dto\TransactionResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UserToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\Transaction;

readonly class TransactionToDtoResultAssembler
{
    public function __construct(
        private UserToDtoResultAssembler $userToDtoResultAssembler
    )
    {
    }

    public function assemble(
        Transaction $transaction
    ): TransactionResultDto {
        $item = new TransactionResultDto();
        $item->id = $transaction->getId()->getValue();
        $item->author = $this->userToDtoResultAssembler->assemble($transaction->getUser());
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
