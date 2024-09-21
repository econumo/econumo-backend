<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\BudgetAccess;
use App\Domain\Entity\BudgetEntityOption;
use App\Domain\Entity\BudgetEnvelope;
use App\Domain\Entity\BudgetFolder;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetStructureDto
{
    public function __construct(
        public Id $id,
        public Id $ownerUserId,
        public BudgetName $budgetName,
        public DateTimeInterface $startedAt,
        /** @var Id[] */
        public array $excludedAccounts,
        /** @var Id[] */
        public array $includedAccountsIds,
        /** @var Id[] */
        public array $currencies,
        /** @var BudgetFolder[] */
        public array $folders,
        /** @var BudgetEnvelope[] */
        public array $envelopes,
        /** @var Id[] */
        public array $categories,
        /** @var Id[] */
        public array $tags,
        /** @var BudgetEntityOption[] */
        public array $entityOptions,
        /** @var BudgetAccess[] */
        public array $sharedAccess
    ) {
    }
}