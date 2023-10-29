<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\EnvelopeCategoryResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;

readonly class CategoryIdsToDtoResultAssembler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * @param Id[] $categoryIds
     * @return EnvelopeCategoryResultDto[]
     */
    public function assemble(Id $envelopeId, array $categoryIds): array
    {
        $result = [];
        $categories = $this->categoryRepository->getByIds($categoryIds);
        foreach ($categories as $category) {
            $dto = new EnvelopeCategoryResultDto();
            $dto->id = $category->getId()->getValue();
            $dto->ownerUserId = $category->getUserId()->getValue();
            $dto->name = $category->getName()->getValue();
            $dto->icon = $category->getIcon()->getValue();
            $dto->type = $category->getType()->getAlias();
            $dto->isArchived = $category->isArchived() ? 1 : 0;
            $dto->envelopeId = $envelopeId->getValue();
            $result[] = $dto;
        }
        return $result;
    }
}
