<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction;

use App\Application\Transaction\Transaction\Dto\CreateTransactionV1RequestDto;
use App\Application\Transaction\Transaction\Dto\CreateTransactionV1ResultDto;
use App\Application\Transaction\Transaction\Assembler\CreateTransactionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;

class TransactionService
{
    private CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler;

    public function __construct(
        CreateTransactionV1ResultAssembler $createTransactionV1ResultAssembler
    ) {
        $this->createTransactionV1ResultAssembler = $createTransactionV1ResultAssembler;
    }

    public function createTransaction(
        CreateTransactionV1RequestDto $dto,
        Id $userId
    ): CreateTransactionV1ResultDto {
        // some actions ...
        return $this->createTransactionV1ResultAssembler->assemble($dto);
    }
}
