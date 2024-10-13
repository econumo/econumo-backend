<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Dto\AccountResultDto;
use App\EconumoOneBundle\Application\Account\Assembler\AccountIdToSharedAccessResultAssembler;
use App\EconumoOneBundle\Application\Currency\Assembler\CurrencyIdToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountOptionsRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;

class AccountToDtoV1ResultAssembler
{
    public function __construct(private readonly AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler, private readonly CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler, private readonly FolderRepositoryInterface $folderRepository, private readonly UserIdToDtoResultAssembler $userIdToDtoResultAssembler, private readonly AccountOptionsRepositoryInterface $accountOptionsRepository)
    {
    }

    public function assemble(Id $userId, Account $account): AccountResultDto
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
        $item->currency = $this->currencyIdToDtoV1ResultAssembler->assemble($account->getCurrencyId());
        $item->balance = $account->getBalance();
        $item->type = $account->getType()->getValue();
        $item->icon = $account->getIcon()->getValue();
        $item->sharedAccess = $this->accountIdToSharedAccessResultAssembler->assemble($account->getId());
        $options = $this->accountOptionsRepository->get($account->getId(), $userId);
        $item->position = $options->getPosition();

        return $item;
    }
}
