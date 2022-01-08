<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\AccountResultDto;
use App\Application\Currency\Assembler\CurrencyIdToDtoV1ResultAssembler;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountOptionsRepositoryInterface;
use App\Domain\Repository\FolderRepositoryInterface;

class AccountToDtoV1ResultAssembler
{
    private AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler;
    private CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler;
    private FolderRepositoryInterface $folderRepository;
    private UserIdToDtoResultAssembler $userIdToDtoResultAssembler;
    private AccountOptionsRepositoryInterface $accountOptionsRepository;

    public function __construct(
        AccountIdToSharedAccessResultAssembler $accountIdToSharedAccessResultAssembler,
        CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler,
        FolderRepositoryInterface $folderRepository,
        UserIdToDtoResultAssembler $userIdToDtoResultAssembler,
        AccountOptionsRepositoryInterface $accountOptionsRepository
    ) {
        $this->accountIdToSharedAccessResultAssembler = $accountIdToSharedAccessResultAssembler;
        $this->currencyIdToDtoV1ResultAssembler = $currencyIdToDtoV1ResultAssembler;
        $this->folderRepository = $folderRepository;
        $this->userIdToDtoResultAssembler = $userIdToDtoResultAssembler;
        $this->accountOptionsRepository = $accountOptionsRepository;
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
        $item->name = $account->getName();
        $item->currency = $this->currencyIdToDtoV1ResultAssembler->assemble($account->getCurrencyId());
        $item->balance = $account->getBalance();
        $item->type = $account->getType()->getValue();
        $item->icon = $account->getIcon();
        $item->sharedAccess = $this->accountIdToSharedAccessResultAssembler->assemble($account->getId());
        $options = $this->accountOptionsRepository->get($account->getId(), $userId);
        $item->position = $options->getPosition();

        return $item;
    }
}
