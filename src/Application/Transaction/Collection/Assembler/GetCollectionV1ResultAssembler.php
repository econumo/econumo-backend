<?php

declare(strict_types=1);

namespace App\Application\Transaction\Collection\Assembler;

use App\Application\Transaction\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Transaction\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Transaction\Collection\Dto\TransactionResultDto;
use App\Domain\Entity\Transaction;

class GetCollectionV1ResultAssembler
{
    /**
     * @param GetCollectionV1RequestDto $dto
     * @param Transaction[] $transactions
     * @return GetCollectionV1ResultDto
     */
    public function assemble(
        GetCollectionV1RequestDto $dto,
        array $transactions
    ): GetCollectionV1ResultDto {
        $result = new GetCollectionV1ResultDto();
        $result->items = [];
        foreach ($transactions as $transaction) {
            $item = new TransactionResultDto();
            $item->id = $transaction->getId()->getValue();
            $item->authorId = $transaction->getUserId()->getValue();
            $item->type = $transaction->getType()->getAlias();
            $item->accountId = $transaction->getAccountId()->getValue();
            $item->accountRecipientId = $transaction->getAccountRecipientId() === null ? null : $transaction->getAccountRecipientId()->getValue();
            $item->amount = $transaction->getAmount();
            $item->amountRecipient = $transaction->getAmount();
            $item->categoryId = $transaction->getCategoryId()->getValue();
            $item->description = $transaction->getDescription();
            $item->payeeId = $transaction->getPayeeId() === null ? null : $transaction->getPayeeId()->getValue();
            $item->tagId = $transaction->getTagId() === null ? null : $transaction->getTagId()->getValue();
            $item->date = $transaction->getSpentAt()->format('Y-m-d H:i:s');
            $result->items[] = $item;
        }

        return $result;
    }
}
