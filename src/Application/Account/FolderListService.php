<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\GetFolderListV1ResultAssembler;
use App\Application\Account\Dto\GetFolderListV1RequestDto;
use App\Application\Account\Dto\GetFolderListV1ResultDto;
use App\Application\Account\Dto\OrderFolderListV1RequestDto;
use App\Application\Account\Dto\OrderFolderListV1ResultDto;
use App\Application\Account\Assembler\OrderFolderListV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\FolderServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

readonly class FolderListService
{
    public function __construct(
        private GetFolderListV1ResultAssembler $getFolderListV1ResultAssembler,
        private FolderRepositoryInterface $folderRepository,
        private OrderFolderListV1ResultAssembler $orderFolderListV1ResultAssembler,
        private FolderServiceInterface $folderService,
        private TranslationServiceInterface $translationService
    ) {
    }

    public function getFolderList(
        GetFolderListV1RequestDto $dto,
        Id $userId
    ): GetFolderListV1ResultDto {
        $folders = $this->folderRepository->getByUserId($userId);
        return $this->getFolderListV1ResultAssembler->assemble($dto, $folders);
    }

    public function orderFolderList(
        OrderFolderListV1RequestDto $dto,
        Id $userId
    ): OrderFolderListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('account.folder_list.empty_list'));
        }

        $this->folderService->orderFolders($userId, $dto->changes);
        return $this->orderFolderListV1ResultAssembler->assemble($dto, $userId);
    }
}
