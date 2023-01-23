<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\OrderAccountListV1RequestDto;
use App\Application\Account\Dto\OrderAccountListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class OrderAccountListV1ResultAssembler
{
    public function __construct(private readonly AccountRepositoryInterface $accountRepository, private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        OrderAccountListV1RequestDto $dto,
        Id $userId
    ): OrderAccountListV1ResultDto {
        $result = new OrderAccountListV1ResultDto();
        $result->items = [];
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        foreach ($accounts as $account) {
            $result->items[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
