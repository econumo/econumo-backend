<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\GetAccountListV1ResultAssembler;
use App\Application\Account\Dto\GetAccountListV1RequestDto;
use App\Application\Account\Dto\GetAccountListV1ResultDto;
use App\Application\Account\Dto\OrderAccountListV1RequestDto;
use App\Application\Account\Dto\OrderAccountListV1ResultDto;
use App\Application\Account\Assembler\OrderAccountListV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\AccountServiceInterface;

class AccountListService
{
    private GetAccountListV1ResultAssembler $getAccountListV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;
    private OrderAccountListV1ResultAssembler $orderAccountListV1ResultAssembler;
    private AccountServiceInterface $accountService;

    public function __construct(
        GetAccountListV1ResultAssembler $getAccountListV1ResultAssembler,
        AccountRepositoryInterface $accountRepository,
        OrderAccountListV1ResultAssembler $orderAccountListV1ResultAssembler,
        AccountServiceInterface $accountService
    ) {
        $this->getAccountListV1ResultAssembler = $getAccountListV1ResultAssembler;
        $this->accountRepository = $accountRepository;
        $this->orderAccountListV1ResultAssembler = $orderAccountListV1ResultAssembler;
        $this->accountService = $accountService;
    }

    public function getAccountList(
        GetAccountListV1RequestDto $dto,
        Id $userId
    ): GetAccountListV1ResultDto {
        $accounts = $this->accountRepository->getAvailableForUserId($userId);
        return $this->getAccountListV1ResultAssembler->assemble($dto, $userId, $accounts);
    }

    public function orderAccountList(
        OrderAccountListV1RequestDto $dto,
        Id $userId
    ): OrderAccountListV1ResultDto {
        if (!count($dto->changes)) {
            throw new ValidationException('Account list is empty');
        }
        $this->accountService->orderAccounts($userId, ...$dto->changes);
        return $this->orderAccountListV1ResultAssembler->assemble($dto, $userId);
    }
}
