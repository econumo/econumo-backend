<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Transaction\Dto\GetCollectionV1RequestDto;
use App\Application\Transaction\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Transaction;

class GetCollectionV1ResultAssembler
{
    private TransactionToDtoV1ResultAssembler $transactionToDtoV1ResultAssembler;

    public function __construct(
        TransactionToDtoV1ResultAssembler $transactionToDtoV1ResultAssembler
    ) {
        $this->transactionToDtoV1ResultAssembler = $transactionToDtoV1ResultAssembler;
    }

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
            $result->items[] = $this->transactionToDtoV1ResultAssembler->assemble($transaction);
        }

        return $result;
    }
}
