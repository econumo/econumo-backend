<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;

readonly class BudgetElementAmountDto
{
    public function __construct(
        public Id $elementId,
        public BudgetElementType $elementType,
        public ?Id $tagId,
        public ?float $budgeted,
        public float $budgetedBefore,
        /** @var BudgetElementAmountSpentDto[] */
        public array $currenciesSpent = [],
        /** @var BudgetElementAmountSpentDto[] */
        public array $currenciesSpentBefore = [],
    ) {
    }
}
