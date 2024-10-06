<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;

readonly class BudgetEntityAmountDto
{
    public function __construct(
        public Id $entityId,
        public BudgetEntityType $entityType,
        public ?Id $tagId,
        public ?float $budgeted,
        public float $budgetedBefore,
        /** @var BudgetEntityAmountSpentDto[] */
        public array $currenciesSpent = [],
        /** @var BudgetEntityAmountSpentDto[] */
        public array $currenciesSpentBefore = [],
    ) {
    }
}
