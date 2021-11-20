<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use App\Domain\Service\Dto\TransactionDto;
use DateTime;

class RequestToDomainDtoAssembler
{
    public function assemble(
        CreateTransactionV1RequestDto $dto,
        Id $userId
    ): TransactionDto {
        $result = new TransactionDto();
        $result->id = new Id($dto->id);
        $result->type = TransactionType::createFromAlias($dto->type);
        $result->userId = $userId;
        $result->amount = $dto->amount;
        $result->accountId = new Id($dto->accountId);
        $result->description = $dto->description === null ? '' : $dto->description;
        $result->date = DateTime::createFromFormat('Y-m-d H:i:s', $dto->date);

        if ($result->type->isTransfer()) {
            if ($dto->amountRecipient !== null) {
                $result->amountRecipient = $dto->amountRecipient;
            }
            if ($dto->accountRecipientId !== null) {
                $result->accountRecipientId = new Id($dto->accountRecipientId);
            }
        } else {
            $result->categoryId = new Id($dto->categoryId);
            if ($dto->payeeId !== null) {
                $result->payeeId = new Id($dto->payeeId);
            }
            if ($dto->tagId !== null) {
                $result->tagId = new Id($dto->tagId);
            }
        }

        return $result;
    }
}
