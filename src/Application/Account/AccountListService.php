<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Dto\GetAccountListV1RequestDto;
use App\Application\Account\Dto\GetAccountListV1ResultDto;
use App\Application\Account\Assembler\GetAccountListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class AccountListService
{
    private GetAccountListV1ResultAssembler $getAccountListV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        GetAccountListV1ResultAssembler $getAccountListV1ResultAssembler,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->getAccountListV1ResultAssembler = $getAccountListV1ResultAssembler;
        $this->accountRepository = $accountRepository;
    }

    public function getAccountList(
        GetAccountListV1RequestDto $dto,
        Id $userId
    ): GetAccountListV1ResultDto {
        $accounts = $this->accountRepository->findByUserId($userId);
        return $this->getAccountListV1ResultAssembler->assemble($dto, $userId, $accounts);
    }
}
