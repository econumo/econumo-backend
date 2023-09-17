<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetPlanListV1RequestDto;
use App\Application\Budget\Dto\GetPlanListV1ResultDto;
use App\Application\Budget\Dto\PlanPreviewResultDto;

class GetPlanListV1ResultAssembler
{
    public function assemble(
        GetPlanListV1RequestDto $dto
    ): GetPlanListV1ResultDto {
        $result = new GetPlanListV1ResultDto();
        $result->items = [];

        // todo
        $item1 = new PlanPreviewResultDto();
        $item1->id = 'b97100fc-3269-4073-a011-c5f595b283d0';
        $item1->ownerUserId = '2ed933d3-f919-49db-aa9a-c3680b2ec8c8';
        $item1->name = 'Family budget';
        $item1->position = 0;
        $item1->isArchived = 0;
        $item1->createdAt = '2021-01-01 12:15:00';
        $item1->updatedAt = '2021-01-01 12:15:00';
        $result->items[] = $item1;

        $item2 = new PlanPreviewResultDto();
        $item2->id = 'b14b4662-4ec6-42c1-ad8e-f2c99f289f43';
        $item2->ownerUserId = '2ed933d3-f919-49db-aa9a-c3680b2ec8c8';
        $item2->name = 'Personal budget';
        $item2->position = 1;
        $item2->isArchived = 0;
        $item2->createdAt = '2021-01-01 12:15:00';
        $item2->updatedAt = '2021-01-01 12:15:00';
        $result->items[] = $item2;

        return $result;
    }
}
