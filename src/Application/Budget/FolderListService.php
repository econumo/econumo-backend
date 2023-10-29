<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\OrderFolderListV1RequestDto;
use App\Application\Budget\Dto\OrderFolderListV1ResultDto;
use App\Application\Budget\Assembler\OrderFolderListV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanFolderServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

readonly class FolderListService
{
    public function __construct(
        private OrderFolderListV1ResultAssembler $orderFolderListV1ResultAssembler,
        private TranslationServiceInterface $translationService,
        private PlanAccessServiceInterface $planAccessService,
        private PlanFolderServiceInterface $planFolderService,
        private PlanFolderRepositoryInterface $planFolderRepository
    ){
    }

    public function orderFolderList(
        OrderFolderListV1RequestDto $dto,
        Id $userId
    ): OrderFolderListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('budget.folder_list.empty_list'));
        }
        $planId = new Id($dto->planId);
        foreach ($dto->changes as $change) {
            $folder = $this->planFolderRepository->get($change->getId());
            if (!$folder->getPlan()->getId()->isEqual($planId)) {
                throw new ValidationException($this->translationService->trans('budget.folder_list.ordering_error'));
            }
        }
        if (!$this->planAccessService->canUpdatePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }

        $this->planFolderService->orderFolders($planId, $dto->changes);
        return $this->orderFolderListV1ResultAssembler->assemble($dto, $planId);
    }
}
