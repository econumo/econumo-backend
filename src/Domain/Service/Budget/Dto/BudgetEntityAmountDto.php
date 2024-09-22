<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;

readonly class BudgetEntityAmountDto
{
    public function __construct(
        public Id $entityId,
        public BudgetEntityType $entityType,
        public ?float $budget,
        public ?float $available,
        /** @var BudgetEntityAmountSpentDto[] */
        public array $spent = [],
    ) {
    }
}
