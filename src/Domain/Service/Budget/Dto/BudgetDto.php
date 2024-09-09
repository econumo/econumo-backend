<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetDto
{
    public function __construct(
        public Id $id,
        public Id $ownerUserId,
        public BudgetName $budgetName,
        public DateTimeInterface $startDate,
        public array $excludedAccounts,
        public array $currencies,
        public array $folders,
        public array $envelopes,
        public array $categories,
        public array $tags,
        public array $sharedAccess
    ) {
    }
}