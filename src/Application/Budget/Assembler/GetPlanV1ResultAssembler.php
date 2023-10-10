<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\DetailedPlanResultDto;
use App\Application\Budget\Dto\GetPlanV1RequestDto;
use App\Application\Budget\Dto\GetPlanV1ResultDto;
use App\Application\Currency\Assembler\CurrencyIdToDtoV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PlanDto;

readonly class GetPlanV1ResultAssembler
{
    public function __construct(
        private CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler
    )
    {
    }

    public function assemble(
        GetPlanV1RequestDto $dto,
        PlanDto $plan,
        Id $userId
    ): GetPlanV1ResultDto {
        $result = new GetPlanV1ResultDto();
        $result->item = new DetailedPlanResultDto();
        $result->item->id = $plan->id->getValue();
        $result->item->name = $plan->name->getValue();
        $result->item->ownerUserId = $plan->ownerUserId->getValue();
        $result->item->createdAt = $plan->createdAt->format('Y-m-d H:i:s');
        $result->item->updatedAt = $plan->updatedAt->format('Y-m-d H:i:s');
        $result->item->currencies = [];
        foreach ($plan->currencies as $currencyId) {
            $result->item->currencies[] = $this->currencyIdToDtoV1ResultAssembler->assemble($currencyId);
        }

        $result->item->folders = [];
        $result->item->envelopes = [];
        $result->item->categories = [];
        $result->item->tags = [];
        $result->item->sharedAccess = [];


        return $result;
    }
}
