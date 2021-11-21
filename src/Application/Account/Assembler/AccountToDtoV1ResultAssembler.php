<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AccountResultDto;
use App\Application\Account\Dto\AccountRoleResultDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class AccountToDtoV1ResultAssembler
{
    private CurrencyRepositoryInterface $currencyRepository;
    private AccountAccessRepositoryInterface $accountAccessRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
        AccountAccessRepositoryInterface $accountAccessRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->accountAccessRepository = $accountAccessRepository;
        $this->userRepository = $userRepository;
    }

    public function assemble(Id $userId, Account $account): AccountResultDto
    {
        $item = new AccountResultDto();
        $item->id = $account->getId()->getValue();
        $item->ownerId = $account->getUserId()->getValue();
        $item->name = $account->getName();
        $item->position = $account->getPosition();
        $item->currencyId = $account->getCurrencyId()->getValue();
        try {
            $currency = $this->currencyRepository->get($account->getCurrencyId());
        } catch (NotFoundException $exception) {
            $currency = null;
        }
        $item->currencySign = $currency !== null ? $currency->getSign() : '';
        $item->currencyAlias = $currency !== null ? $currency->getAlias() : '';
        $item->balance = $account->getBalance();
        $item->type = $account->getType()->getValue();
        $item->icon = $account->getIcon();

        $accessList = $this->accountAccessRepository->getByAccount($account->getId());
        foreach ($accessList as $access) {
            $user = $this->userRepository->get($access->getUserId());
            $sharedAccess = new AccountRoleResultDto();
            $sharedAccess->userId = $access->getUserId()->getValue();
            $sharedAccess->userAvatar = $user->getAvatarUrl();
            $sharedAccess->role = $access->getRole()->getAlias();
            $item->sharedAccess[] = $sharedAccess;
        }
        return $item;
    }
}
