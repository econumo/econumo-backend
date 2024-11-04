<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Transaction\Assembler;

use App\EconumoOneBundle\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Transaction\Assembler\TransactionToDtoResultAssembler;
use App\EconumoOneBundle\Application\Transaction\Dto\UpdateTransactionV1RequestDto;
use App\EconumoOneBundle\Application\Transaction\Dto\UpdateTransactionV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;

class UpdateTransactionV1ResultAssembler
{
    public function __construct(private readonly AccountRepositoryInterface $accountRepository, private readonly TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler, private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        UpdateTransactionV1RequestDto $dto,
        Id $userId,
        Transaction $transaction
    ): UpdateTransactionV1ResultDto {
        $result = new UpdateTransactionV1ResultDto();
        $result->item = $this->transactionToDtoV1ResultAssembler->assemble($transaction);
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        foreach (array_reverse($accounts) as $account) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
