<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget;

use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetCategoryDto;
use Throwable;

interface BudgetElementServiceInterface
{

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return array [int, BudgetCategoryDto[]]
     */
    public function createCategoriesElements(Id $userId, Id $budgetId, int $startPosition = 0): array;

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return array [int, BudgetTagDto[]]
     */
    public function createTagsElements(Id $userId, Id $budgetId, int $startPosition = 0): array;
}
