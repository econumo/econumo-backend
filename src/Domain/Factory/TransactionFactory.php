<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Transaction;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\Dto\TransactionDto;

class TransactionFactory implements TransactionFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(TransactionDto $dto): Transaction
    {
        return new Transaction(
            $dto->id,
            $dto->userId,
            $dto->type,
            $dto->accountId,
            $dto->categoryId,
            $dto->amount,
            $dto->date,
            $this->datetimeService->getCurrentDatetime(),
            $dto->accountRecipientId,
            $dto->amountRecipient,
            $dto->description,
            $dto->payeeId,
            $dto->tagId
        );
    }
}
