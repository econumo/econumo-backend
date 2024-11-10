<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Transaction\Assembler;

use App\EconumoOneBundle\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Transaction\Dto\CreateTransactionV1RequestDto;
use App\EconumoOneBundle\Application\Transaction\Dto\CreateTransactionV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AccountServiceInterface;

readonly class CreateTransactionV1ResultAssembler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionToDtoResultAssembler $transactionToDtoV1ResultAssembler,
        private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler,
        private AccountServiceInterface $accountService
    ) {
    }

    public function assemble(
        CreateTransactionV1RequestDto $dto,
        Id $userId,
        Transaction $transaction
    ): CreateTransactionV1ResultDto {
        $result = new CreateTransactionV1ResultDto();
        $result->item = $this->transactionToDtoV1ResultAssembler->assemble($transaction);
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        $accountsIds = array_map(fn(Account $account) => $account->getId(), $accounts);
        $balances = $this->accountService->getAccountsBalance($accountsIds);
        foreach (array_reverse($accounts) as $account) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble(
                $userId,
                $account,
                $balances[$account->getId()->getValue()] ?? .0
            );
        }

        return $result;
    }
}
