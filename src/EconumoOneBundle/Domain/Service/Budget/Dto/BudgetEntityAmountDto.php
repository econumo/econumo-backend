<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEntityAmountSpentDto;

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