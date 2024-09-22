<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;

readonly class BudgetDtoToResultDtoAssembler
{
    public function __construct(
        private BudgetAccessToResultDtoAssembler $budgetAccessToResultDtoAssembler,
        private BudgetFolderToResultDtoAssembler $budgetFolderToResultDtoAssembler,
        private BudgetEnvelopeToResultDtoAssembler $budgetEnvelopeToResultDtoAssembler,
        private BudgetEntityOptionToResultDtoAssembler $budgetEntityOptionToResultDtoAssembler
    ) {
    }

    public function assemble(Id $userId, BudgetStructureDto $budgetDto): BudgetResultDto
    {
        $result = new BudgetResultDto();
        $result->id = $budgetDto->id->getValue();
        $result->name = $budgetDto->budgetName->getValue();
        $result->ownerUserId = $budgetDto->ownerUserId->getValue();
        $result->startedAt = $budgetDto->startedAt->format('Y-m-d H:i:s');
        $result->excludedAccounts = [];
        foreach ($budgetDto->excludedAccounts as $accountId) {
            $result->excludedAccounts[] = $accountId->getValue();
        }
        $result->currencies = [];
        foreach ($budgetDto->currencies as $currencyId) {
            $result->currencies[] = $currencyId->getValue();
        }
        $result->folders = [];
        foreach ($budgetDto->folders as $budgetFolder) {
            $result->folders[] = $this->budgetFolderToResultDtoAssembler->assemble($budgetFolder);
        }
        $result->envelopes = [];
        foreach ($budgetDto->envelopes as $budgetEnvelope) {
            $result->envelopes[] = $this->budgetEnvelopeToResultDtoAssembler->assemble($budgetEnvelope);
        }
        $result->categories = [];
//        foreach ($budgetDto->categories as $categoryId) {
//            $result->categories[] = $categoryId->getValue();
//        }
        $result->tags = [];
//        foreach ($budgetDto->tags as $tagId) {
//            $result->tags[] = $tagId->getValue();
//        }
        $result->entityOptions = [];
        foreach ($budgetDto->entityOptions as $entityOption) {
            $result->entityOptions[] = $this->budgetEntityOptionToResultDtoAssembler->assemble($entityOption);
        }

        $result->sharedAccess = [];
        foreach ($budgetDto->sharedAccess as $budgetAccess) {
            $result->sharedAccess[] = $this->budgetAccessToResultDtoAssembler->assemble($budgetAccess);
        }

        return $result;
    }
}
