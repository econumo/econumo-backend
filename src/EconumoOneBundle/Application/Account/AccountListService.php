<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account;

use App\EconumoOneBundle\Application\Account\Assembler\GetAccountListV1ResultAssembler;
use App\EconumoOneBundle\Application\Account\Dto\GetAccountListV1RequestDto;
use App\EconumoOneBundle\Application\Account\Dto\GetAccountListV1ResultDto;
use App\EconumoOneBundle\Application\Account\Dto\OrderAccountListV1RequestDto;
use App\EconumoOneBundle\Application\Account\Dto\OrderAccountListV1ResultDto;
use App\EconumoOneBundle\Application\Account\Assembler\OrderAccountListV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AccountServiceInterface;
use App\EconumoOneBundle\Domain\Service\Translation\TranslationServiceInterface;

class AccountListService
{
    public function __construct(private readonly GetAccountListV1ResultAssembler $getAccountListV1ResultAssembler, private readonly AccountRepositoryInterface $accountRepository, private readonly OrderAccountListV1ResultAssembler $orderAccountListV1ResultAssembler, private readonly AccountServiceInterface $accountService, private readonly TranslationServiceInterface $translationService)
    {
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
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('account.account_list.empty_list'));
        }

        $this->accountService->orderAccounts($userId, $dto->changes);
        return $this->orderAccountListV1ResultAssembler->assemble($dto, $userId);
    }
}
