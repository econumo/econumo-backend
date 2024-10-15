<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\CreateFolderV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\CreateFolderV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\CreateFolderV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetFolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\FolderServiceInterface;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteFolderV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteFolderV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\DeleteFolderV1ResultAssembler;

readonly class FolderService
{
    public function __construct(
        private CreateFolderV1ResultAssembler $createFolderV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private FolderServiceInterface $folderService,
        private DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler,
        private BudgetFolderRepositoryInterface $budgetFolderRepository,
    ) {
    }

    public function createFolder(
        CreateFolderV1RequestDto $dto,
        Id $userId
    ): CreateFolderV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $folderId = new Id($dto->id);
        $folderName = new BudgetFolderName($dto->name);
        $folder = $this->folderService->create($budgetId, $folderId, $folderName);
        return $this->createFolderV1ResultAssembler->assemble($folder);
    }

    public function deleteFolder(
        DeleteFolderV1RequestDto $dto,
        Id $userId
    ): DeleteFolderV1ResultDto {
        $folderId = new Id($dto->id);
        $folder = $this->budgetFolderRepository->get($folderId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $folder->getBudget()->getId())) {
            throw new AccessDeniedException();
        }

        $this->folderService->delete($folderId);
        return $this->deleteFolderV1ResultAssembler->assemble($dto);
    }
}
