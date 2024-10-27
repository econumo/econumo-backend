<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget;

use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\Tag;
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

    public function createCategoryElements(Category $category): void;

    public function deleteCategoryElements(Id $categoryId): void;

    public function archiveCategoryElements(Id $categoryId): void;

    public function unarchiveCategoryElements(Id $categoryId): void;

    public function createTagElements(Tag $tag): void;

    public function deleteTagElements(Id $tagId): void;

    public function archiveTagElements(Id $tagId): void;

    public function unarchiveTagElements(Id $tagId): void;

    public function deleteEnvelopeElement(Id $envelopeId): void;

    public function archiveEnvelopeElement(Id $envelopeId): void;

    public function unarchiveEnvelopeElement(Id $envelopeId): void;
}
