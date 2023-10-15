<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreateFolderV1RequestDto;
use App\Application\Budget\Dto\CreateFolderV1ResultDto;
use App\Application\Budget\Assembler\CreateFolderV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanFolderServiceInterface;

readonly class FolderService
{
    public function __construct(
        private CreateFolderV1ResultAssembler $createFolderV1ResultAssembler,
        private PlanAccessServiceInterface $planAccessService,
        private PlanFolderServiceInterface $planFolderService
    ) {
    }

    public function createFolder(
        CreateFolderV1RequestDto $dto,
        Id $userId
    ): CreateFolderV1ResultDto {
        $planId = new Id($dto->planId);
        if (!$this->planAccessService->canUpdatePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $folderId = $this->planFolderService->createFolder($planId, new PlanFolderName($dto->name));
        return $this->createFolderV1ResultAssembler->assemble($dto, $folderId);
    }
}
