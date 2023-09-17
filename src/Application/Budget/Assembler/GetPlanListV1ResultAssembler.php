<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetPlanListV1RequestDto;
use App\Application\Budget\Dto\GetPlanListV1ResultDto;
use App\Application\Budget\Dto\PlanPreviewResultDto;
use App\Application\Budget\Dto\SharedAccessItemResultDto;
use App\Application\User\Dto\UserResultDto;

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
        $item1->ownerUserId = 'aff21334-96f0-4fb1-84d8-0223d0280954';
        $item1->name = 'Family budget';
        $item1->position = 0;
        $item1->isArchived = 0;
        $item1->createdAt = '2021-01-01 12:15:00';
        $item1->updatedAt = '2021-01-01 12:15:00';
        $item1->sharedAccess = [];
        $userOwner = new SharedAccessItemResultDto();
        $userOwner->user = new UserResultDto();
        $userOwner->user->id = 'aff21334-96f0-4fb1-84d8-0223d0280954';
        $userOwner->user->name = 'John';
        $userOwner->user->avatar = 'https://www.gravatar.com/avatar/f888aa10236977f30255dea605ec99d0';
        $userOwner->role = 'admin';
        $item1->sharedAccess[] = $userOwner;
        $secondUser = new SharedAccessItemResultDto();
        $secondUser->user = new UserResultDto();
        $secondUser->user->id = '77be9577-147b-4f05-9aa7-91d9b159de5b';
        $secondUser->user->name = 'Dany';
        $secondUser->user->avatar = 'https://www.gravatar.com/avatar/f888aa10236977f30255dea605ec99d0';
        $secondUser->role = 'guest';
        $item1->sharedAccess[] = $secondUser;
        $result->items[] = $item1;

        $item2 = new PlanPreviewResultDto();
        $item2->id = 'b14b4662-4ec6-42c1-ad8e-f2c99f289f43';
        $item2->ownerUserId = 'aff21334-96f0-4fb1-84d8-0223d0280954';
        $item2->name = 'Personal budget';
        $item2->position = 1;
        $item2->isArchived = 0;
        $item2->createdAt = '2021-01-01 12:15:00';
        $item2->updatedAt = '2021-01-01 12:15:00';
        $item2->sharedAccess = [];
        $item2->sharedAccess[] = $userOwner;
        $result->items[] = $item2;

        return $result;
    }
}
