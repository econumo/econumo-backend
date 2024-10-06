<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget;

use App\EconumoBundle\Domain\Entity\BudgetEntityOption;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetCategoryDto;
use Throwable;

interface BudgetEntityServiceInterface
{

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return BudgetCategoryDto[]
     * @throws Throwable
     */
    public function createCategoriesOptions(Id $userId, Id $budgetId, int $startPosition = 0): array;

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return BudgetEntityOption[]
     * @throws Throwable
     */
    public function createTagsOptions(Id $userId, Id $budgetId, int $startPosition = 0): array;
}
