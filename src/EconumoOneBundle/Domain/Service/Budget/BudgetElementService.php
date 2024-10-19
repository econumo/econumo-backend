<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget;

use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetElementOptionFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementOptionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetElementServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetCategoryDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetTagDto;
use Throwable;

readonly class BudgetElementService implements BudgetElementServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private TagRepositoryInterface $tagRepository,
        private BudgetElementOptionFactoryInterface $budgetEntityOptionFactory,
        private BudgetElementOptionRepositoryInterface $budgetEntityOptionRepository
    ) {
    }

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return array [int, BudgetCategoryDto[]]
     */
    public function createCategoriesOptions(Id $userId, Id $budgetId, int $startPosition = 0): array
    {
        $result = [];
        $categories = $this->categoryRepository->findByOwnerId($userId);
        $position = $startPosition;
        $entities = [];
        foreach ($categories as $category) {
            if ($category->getType()->isIncome()) {
                continue;
            }

            $item = $this->budgetEntityOptionFactory->createCategoryOption(
                $budgetId,
                $category->getId(),
                ($category->isArchived() ? BudgetElementOption::POSITION_UNSET : $position++)
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

        return [$position, $result];
    }

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param int $startPosition
     * @return array [int, BudgetTagDto[]]
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
                ($tag->isArchived() ? BudgetElementOption::POSITION_UNSET : $position++)
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

        return [$position, $result];
    }
}
