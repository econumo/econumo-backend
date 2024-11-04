<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\Tag;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetFiltersDto
{
    public function __construct(
        public DateTimeInterface $periodStart,
        public DateTimeInterface $periodEnd,
        /** @var Id[] */
        public array $userIds,
        /** @var Id[] */
        public array $excludedAccountsIds,
        /** @var Id[] */
        public array $includedAccountsIds,
        /** @var Id[] */
        public array $currenciesIds,
        /** @var Category[] */
        public array $categories,
        /** @var Tag[] */
        public array $tags
    ) {
    }
}