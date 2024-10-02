<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDto;
use App\Domain\Service\Budget\Dto\BudgetFiltersDto;

readonly class BudgetDtoToResultDtoAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaToResultDtoAssembler,
        private BudgetStructureToResultDtoAssembler $budgetStructureToResultDtoAssembler,
        private BudgetCurrencyBalanceToResultDtoAssembler $budgetCurrencyBalanceToResultDtoAssembler,
//        private BudgetFolderToResultDtoAssembler $budgetFolderToResultDtoAssembler,
//        private BudgetEnvelopeToResultDtoAssembler $budgetEnvelopeToResultDtoAssembler,
//        private BudgetEntityOptionToResultDtoAssembler $budgetEntityOptionToResultDtoAssembler
    ) {
    }

    public function assemble(Id $userId, BudgetDto $dto): BudgetResultDto
    {
        $result = new BudgetResultDto();
        $result->meta = $this->budgetMetaToResultDtoAssembler->assemble($dto->meta);
        $result->balances = [];
        foreach ($dto->financialSummary->currencyBalances as $balance) {
            $result->balances[] = $this->budgetCurrencyBalanceToResultDtoAssembler->assemble($balance);
        }
        $result->structure = $this->budgetStructureToResultDtoAssembler->assemble($dto->structure);

        /*$result->excludedAccounts = [];
        foreach ($dto->excludedAccounts as $accountId) {
            $result->excludedAccounts[] = $accountId->getValue();
        }
        $result->currencies = [];
        foreach ($dto->currenciesIds as $currencyId) {
            $result->currencies[] = $currencyId->getValue();
        }
        $result->folders = [];
        foreach ($dto->folders as $budgetFolder) {
            $result->folders[] = $this->budgetFolderToResultDtoAssembler->assemble($budgetFolder);
        }
        $result->envelopes = [];
        foreach ($dto->envelopes as $budgetEnvelope) {
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
        foreach ($dto->entityOptions as $entityOption) {
            $result->entityOptions[] = $this->budgetEntityOptionToResultDtoAssembler->assemble($entityOption);
        }*/

        return $result;
    }
}
