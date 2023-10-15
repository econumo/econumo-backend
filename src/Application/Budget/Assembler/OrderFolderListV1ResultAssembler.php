<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\OrderFolderListV1RequestDto;
use App\Application\Budget\Dto\OrderFolderListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanFolderRepositoryInterface;

readonly class OrderFolderListV1ResultAssembler
{
    public function __construct(
        private PlanFolderRepositoryInterface $planFolderRepository,
        private FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler
    ) {
    }

    public function assemble(
        OrderFolderListV1RequestDto $dto,
        Id $planId
    ): OrderFolderListV1ResultDto {
        $result = new OrderFolderListV1ResultDto();
        $result->items = [];
        $folders = $this->planFolderRepository->getByPlanId($planId);
        foreach ($folders as $folder) {
            $result->items[] = $this->folderToDtoV1ResultAssembler->assemble($folder);
        }

        return $result;
    }
}
