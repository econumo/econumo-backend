<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\DetailedPlanResultDto;
use App\Application\Budget\Dto\GetPlanV1RequestDto;
use App\Application\Budget\Dto\GetPlanV1ResultDto;
use App\Application\Budget\Dto\PlanSharedAccessItemResultDto;
use App\Application\Currency\Assembler\CurrencyIdToDtoV1ResultAssembler;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Service\Dto\PlanDto;

readonly class GetPlanV1ResultAssembler
{
    public function __construct(
        private CurrencyIdToDtoV1ResultAssembler $currencyIdToDtoV1ResultAssembler,
        private FolderIdToDtoV1ResultAssembler $folderIdToDtoV1ResultAssembler,
        private SharedAccessIdToResultDtoAssembler $sharedAccessIdToResultDtoAssembler,
        private EnvelopeIdToDtoResultAssembler $envelopeIdToDtoResultAssembler,
        private UserIdToDtoResultAssembler $userIdToDtoResultAssembler,
        private CategoryIdsToDtoResultAssembler $categoryIdsToDtoResultAssembler,
        private TagIdsToDtoResultAssembler $tagIdsToDtoResultAssembler
    )
    {
    }

    public function assemble(
        GetPlanV1RequestDto $dto,
        PlanDto $plan,
        Id $userId
    ): GetPlanV1ResultDto {
        $dto = new GetPlanV1ResultDto();
        $dto->item = new DetailedPlanResultDto();
        $dto->item->id = $plan->id->getValue();
        $dto->item->name = $plan->name->getValue();
        $dto->item->ownerUserId = $plan->ownerUserId->getValue();
        $dto->item->createdAt = $plan->createdAt->format('Y-m-d H:i:s');
        $dto->item->updatedAt = $plan->updatedAt->format('Y-m-d H:i:s');
        $dto->item->envelopes = [];
        foreach ($plan->envelopes as $envelopeId) {
            $dto->item->envelopes[] = $this->envelopeIdToDtoResultAssembler->assemble($envelopeId);
        }
        $dto->item->currencies = [];
        foreach ($plan->currencies as $currencyId) {
            $dto->item->currencies[] = $this->currencyIdToDtoV1ResultAssembler->assemble($currencyId);
        }
        $dto->item->folders = [];
        foreach ($plan->folders as $folderId) {
            $dto->item->folders[] = $this->folderIdToDtoV1ResultAssembler->assemble($folderId);
        }
        $dto->item->categories = [];
        foreach ($plan->categories as $envelopeId => $categoryIds) {
            $dto->item->categories = array_merge($dto->item->categories, $this->categoryIdsToDtoResultAssembler->assemble(new Id($envelopeId), $categoryIds));
        }
        $dto->item->tags = [];
        foreach ($plan->tags as $envelopeId => $tagIds) {
            $dto->item->tags = array_merge($dto->item->tags, $this->tagIdsToDtoResultAssembler->assemble(new Id($envelopeId), $tagIds));
        }
        $dto->item->sharedAccess = [];
        foreach ($plan->sharedAccess as $userId) {
            $dto->item->sharedAccess[] = $this->sharedAccessIdToResultDtoAssembler->assemble($plan->id, $userId);
        }
        $ownerUserAccess = new PlanSharedAccessItemResultDto();
        $ownerUserAccess->isAccepted = 1;
        $ownerUserAccess->role = UserRole::admin()->getAlias();
        $ownerUserAccess->user = $this->userIdToDtoResultAssembler->assemble($plan->ownerUserId);
        $dto->item->sharedAccess[] = $ownerUserAccess;

        return $dto;
    }
}
