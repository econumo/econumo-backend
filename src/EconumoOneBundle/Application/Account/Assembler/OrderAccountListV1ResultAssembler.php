<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Dto\OrderAccountListV1RequestDto;
use App\EconumoOneBundle\Application\Account\Dto\OrderAccountListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AccountServiceInterface;

readonly class OrderAccountListV1ResultAssembler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler,
        private AccountServiceInterface $accountService
    ) {
    }

    public function assemble(
        OrderAccountListV1RequestDto $dto,
        Id $userId
    ): OrderAccountListV1ResultDto {
        $result = new OrderAccountListV1ResultDto();
        $result->items = [];
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        $accountsIds = array_map(fn(Account $account) => $account->getId(), $accounts);
        $balances = $this->accountService->getAccountsBalance($accountsIds);
        foreach ($accounts as $account) {
            $result->items[] = $this->accountToDtoV1ResultAssembler->assemble(
                $userId,
                $account,
                $balances[$account->getId()->getValue()] ?? .0
            );
        }

        return $result;
    }
}
