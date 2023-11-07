<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreateFolderV1RequestDto;
use App\Application\Budget\Dto\CreateFolderV1ResultDto;
use App\Application\Budget\Assembler\CreateFolderV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Exception\PlanFolderIsNotEmptyException;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanFolderServiceInterface;
use App\Application\Budget\Dto\DeleteFolderV1RequestDto;
use App\Application\Budget\Dto\DeleteFolderV1ResultDto;
use App\Application\Budget\Assembler\DeleteFolderV1ResultAssembler;
use App\Domain\Service\Translation\TranslationServiceInterface;
use App\Application\Budget\Dto\UpdateFolderV1RequestDto;
use App\Application\Budget\Dto\UpdateFolderV1ResultDto;
use App\Application\Budget\Assembler\UpdateFolderV1ResultAssembler;

readonly class FolderService
{
    public function __construct(
        private CreateFolderV1ResultAssembler $createFolderV1ResultAssembler,
        private PlanAccessServiceInterface $planAccessService,
        private PlanFolderServiceInterface $planFolderService,
        private DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler,
        private PlanFolderRepositoryInterface $planFolderRepository,
        private TranslationServiceInterface $translationService,
        private UpdateFolderV1ResultAssembler $updateFolderV1ResultAssembler,
    ) {
    }

    public function createFolder(
        CreateFolderV1RequestDto $dto,
        Id $userId
    ): CreateFolderV1ResultDto {
        $planId = new Id($dto->planId);
        if (!$this->planAccessService->canManagePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $folderId = $this->planFolderService->createFolder($planId, new PlanFolderName($dto->name));
        return $this->createFolderV1ResultAssembler->assemble($dto, $folderId);
    }

    public function deleteFolder(
        DeleteFolderV1RequestDto $dto,
        Id $userId
    ): DeleteFolderV1ResultDto {
        $folderId = new Id($dto->id);
        $folder = $this->planFolderRepository->get($folderId);
        $planId = $folder->getPlan()->getId();
        if (!$this->planAccessService->canManagePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }
        try {
            $this->planFolderService->deleteFolder($folderId);
        } catch (PlanFolderIsNotEmptyException $e) {
            throw new ValidationException($this->translationService->trans('budget.plan_folder.is_not_empty'));
        }
        return $this->deleteFolderV1ResultAssembler->assemble($dto);
    }

    public function updateFolder(
        UpdateFolderV1RequestDto $dto,
        Id $userId
    ): UpdateFolderV1ResultDto {
        $folderId = new Id($dto->id);
        $folder = $this->planFolderRepository->get($folderId);
        $planId = $folder->getPlan()->getId();
        if (!$this->planAccessService->canManagePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $this->planFolderService->updateFolder($folderId, new PlanFolderName($dto->name));
        return $this->updateFolderV1ResultAssembler->assemble($dto, $folderId);
    }
}
