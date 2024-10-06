<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction\Assembler;

use App\EconumoBundle\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\EconumoBundle\Application\Transaction\Assembler\TransactionToDtoResultAssembler;
use App\EconumoBundle\Application\Transaction\Dto\DeleteTransactionV1RequestDto;
use App\EconumoBundle\Application\Transaction\Dto\DeleteTransactionV1ResultDto;
use App\EconumoBundle\Domain\Entity\Transaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;

class DeleteTransactionV1ResultAssembler
{
    public function __construct(private readonly AccountRepositoryInterface $accountRepository, private readonly TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler, private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        DeleteTransactionV1RequestDto $dto,
        Id $userId,
        Transaction $transaction
    ): DeleteTransactionV1ResultDto {
        $result = new DeleteTransactionV1ResultDto();
        $result->item = $this->transactionToDtoV1ResultAssembler->assemble($userId, $transaction);
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        foreach (array_reverse($accounts) as $account) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
