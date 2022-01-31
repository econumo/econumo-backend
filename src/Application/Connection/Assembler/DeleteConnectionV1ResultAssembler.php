<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\Application\Connection\Dto\DeleteConnectionV1RequestDto;
use App\Application\Connection\Dto\DeleteConnectionV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class DeleteConnectionV1ResultAssembler
{
    private AccountRepositoryInterface $accountRepository;
    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
    }

    public function assemble(
        DeleteConnectionV1RequestDto $dto,
        Id $userId
    ): DeleteConnectionV1ResultDto {
        $result = new DeleteConnectionV1ResultDto();
        $result->accounts = [];
        foreach ($this->accountRepository->getAvailableForUserId($userId) as $account) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $account);
        }
        return $result;
    }
}
