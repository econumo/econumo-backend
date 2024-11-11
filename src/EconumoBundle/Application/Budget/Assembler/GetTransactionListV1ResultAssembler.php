<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Budget\Assembler;

use App\EconumoBundle\Application\Budget\Dto\BudgetTransactionCategoryResultDto;
use App\EconumoBundle\Application\Budget\Dto\BudgetTransactionPayeeResultDto;
use App\EconumoBundle\Application\Budget\Dto\BudgetTransactionResultDto;
use App\EconumoBundle\Application\Budget\Dto\BudgetTransactionTagResultDto;
use App\EconumoBundle\Application\Budget\Dto\GetTransactionListV1ResultDto;
use App\EconumoBundle\Application\User\Assembler\UserToDtoResultAssembler;
use App\EconumoBundle\Domain\Entity\Transaction;

readonly class GetTransactionListV1ResultAssembler
{
    public function __construct(
        private UserToDtoResultAssembler  $userToDtoResultAssembler
    ) {
    }

    /**
     * @param Transaction[] $transactions
     * @return GetTransactionListV1ResultDto
     */
    public function assemble(
        array $transactions
    ): GetTransactionListV1ResultDto {
        $result = new GetTransactionListV1ResultDto();
        $result->items = [];
        foreach ($transactions as $transaction) {
            $dto = new BudgetTransactionResultDto();
            $dto->id = $transaction->getId()->getValue();
            $dto->author = $this->userToDtoResultAssembler->assemble($transaction->getUser());
            $dto->description = $transaction->getDescription();
            $dto->currencyId = $transaction->getAccountCurrencyId()->getValue();
            $dto->amount = round($transaction->getAmount(), 2);
            $dto->spentAt = $transaction->getSpentAt()->format('Y-m-d H:i:s');
            $dto->category = null;
            if ($transaction->getCategory()) {
                $dto->category = new BudgetTransactionCategoryResultDto();
                $dto->category->id = $transaction->getCategory()->getId()->getValue();
                $dto->category->name = $transaction->getCategory()->getName()->getValue();
                $dto->category->icon = $transaction->getCategory()->getIcon()->getValue();
            }

            $dto->payee = null;
            if ($transaction->getPayee()) {
                $dto->payee = new BudgetTransactionPayeeResultDto();
                $dto->payee->id = $transaction->getPayee()->getId()->getValue();
                $dto->payee->name = $transaction->getPayee()->getName()->getValue();
            }

            $dto->tag = null;
            if ($transaction->getTag()) {
                $dto->tag = new BudgetTransactionTagResultDto();
                $dto->tag->id = $transaction->getTag()->getId()->getValue();
                $dto->tag->name = $transaction->getTag()->getName()->getValue();
            }
            $result->items[] = $dto;
        }

        return $result;
    }
}
