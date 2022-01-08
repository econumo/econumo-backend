<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\OrderAccountV1RequestDto;
use App\Application\Account\Dto\OrderAccountV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class OrderAccountV1ResultAssembler
{
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
        $this->accountRepository = $accountRepository;
    }

    public function assemble(
        OrderAccountV1RequestDto $dto,
        Id $userId
    ): OrderAccountV1ResultDto {
        $result = new OrderAccountV1ResultDto();
        $result->items = [];
        foreach ($this->accountRepository->findByUserId($userId) as $account) {
            $result->items[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }

        return $result;
    }
}
