<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\Category;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use ArrayObject;
use DateTimeInterface;

readonly class BudgetFiltersDto
{
    public function __construct(
        public DateTimeInterface $periodStart,
        public DateTimeInterface $periodEnd,
        /** @var Id[] */
        public array $userIds,
        /** @var Id[] */
        public array $excludedAccounts,
        /** @var Id[] */
        public array $includedAccountsIds,
        /** @var Id[] */
        public array $currenciesIds,
        /** @var Category[] */
        public ArrayObject $categories,
        /** @var Tag[] */
        public ArrayObject $tags
    ) {
    }
}