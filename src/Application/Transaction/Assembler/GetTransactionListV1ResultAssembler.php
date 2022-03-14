<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Transaction\Dto\GetTransactionListV1RequestDto;
use App\Application\Transaction\Dto\GetTransactionListV1ResultDto;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;

class GetTransactionListV1ResultAssembler
{
    private TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler;

    public function __construct(
        TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler
    ) {
        $this->transactionToDtoV1ResultAssembler = $transactionToDtoV1ResultAssembler;
    }

    /**
     * @param GetTransactionListV1RequestDto $dto
     * @param Id $userId
     * @param Transaction[] $transactions
     * @return GetTransactionListV1ResultDto
     */
    public function assemble(
        GetTransactionListV1RequestDto $dto,
        Id $userId,
        array $transactions
    ): GetTransactionListV1ResultDto {
        $result = new GetTransactionListV1ResultDto();
        $result->items = [];
        foreach ($transactions as $transaction) {
            $result->items[] = $this->transactionToDtoV1ResultAssembler->assemble($userId, $transaction);
        }

        return $result;
    }
}
