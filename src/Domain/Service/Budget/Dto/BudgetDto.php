<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\BudgetAccess;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetDto
{
    public function __construct(
        public Id $id,
        public Id $ownerUserId,
        public BudgetName $budgetName,
        public DateTimeInterface $startedAt,
        /** @var Id[] */
        public array $excludedAccounts,
        /** @var BudgetAccess[] */
        public array $sharedAccess
    ) {
    }
}