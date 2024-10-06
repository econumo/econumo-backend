<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget;

use App\EconumoBundle\Domain\Entity\BudgetEntityOption;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Factory\BudgetEntityOptionFactoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoBundle\Domain\Service\Budget\BudgetEntityServiceInterface;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetCategoryDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetTagDto;
use Throwable;

readonly class BudgetEntityService implements BudgetEntityServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private BudgetEntityOptionFactoryInterface $budgetEntityOptionFactory,
        private BudgetEntityOptionRepositoryInterface $budgetEntityOptionRepository
    ) {
    }

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return BudgetCategoryDto[]
     * @throws Throwable
     */
    public function createCategoriesOptions(Id $userId, Id $budgetId, int $startPosition = 0): array
    {
        $result = [];
        $categories = $this->categoryRepository->findByOwnerId($userId);
        $position = $startPosition;
        $entities = [];
        foreach ($categories as $category) {
            $item = $this->budgetEntityOptionFactory->createCategoryOption(
                $budgetId,
                $category->getId(),
                $position++
            );
            $entities[] = $item;
            $result[] = new BudgetCategoryDto(
                $category->getId(),
                $category->getUserId(),
                $budgetId,
                null,
                null,
                $category->getName(),
                $category->getType(),
                $category->getIcon(),
                $item->getPosition(),
                $category->isArchived()
            );
        }
        $this->budgetEntityOptionRepository->save($entities);

        return $result;
    }

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return BudgetTagDto[]
     * @throws Throwable
     */
    public function createTagsOptions(Id $userId, Id $budgetId, int $startPosition = 0): array
    {
        $result = [];
        $position = $startPosition;
        $tags = $this->tagRepository->findByOwnerId($userId);
        $entities = [];
        foreach ($tags as $tag) {
            $item = $this->budgetEntityOptionFactory->createTagOption(
                $budgetId,
                $tag->getId(),
                $position++
            );
            $entities[] = $item;
            $result[] = new BudgetTagDto(
                $tag->getId(),
                $tag->getUserId(),
                $budgetId,
                null,
                null,
                $tag->getName(),
                $tag->getIcon(),
                $item->getPosition(),
                $tag->isArchived()
            );
        }
        $this->budgetEntityOptionRepository->save($entities);

        return $result;
    }
}
