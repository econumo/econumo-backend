<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Dto\AccountResultDto;
use App\EconumoOneBundle\Application\Currency\Assembler\CurrencyToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountOptionsRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;

readonly class AccountToDtoV1ResultAssembler
{
    public function __construct(
        private AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler,
        private CurrencyToDtoV1ResultAssembler $currencyToDtoV1ResultAssembler,
        private FolderRepositoryInterface $folderRepository,
        private UserIdToDtoResultAssembler $userIdToDtoResultAssembler,
        private AccountOptionsRepositoryInterface $accountOptionsRepository
    ) {
    }

    public function assemble(Id $userId, Account $account, float $balance): AccountResultDto
    {
        $item = new AccountResultDto();
        $item->id = $account->getId()->getValue();
        $item->owner = $this->userIdToDtoResultAssembler->assemble($account->getUserId());
        $item->folderId = null;
        $folders = $this->folderRepository->getByUserId($userId);
        foreach ($folders as $folder) {
            if ($folder->containsAccount($account)) {
                $item->folderId = $folder->getId()->getValue();
                break;
            }
        }

        $item->name = $account->getName()->getValue();
        $item->currency = $this->currencyToDtoV1ResultAssembler->assemble($account->getCurrency());
        $item->balance = $balance;
        $item->type = $account->getType()->getValue();
        $item->icon = $account->getIcon()->getValue();
        $item->sharedAccess = $this->accountIdToSharedAccessResultAssembler->assemble($account->getId());
        $options = $this->accountOptionsRepository->get($account->getId(), $userId);
        $item->position = $options->getPosition();

        return $item;
    }
}
