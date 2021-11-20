<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Application\Category\Dto\GetCollectionV1RequestDto;
use App\Application\Category\Dto\GetCollectionV1ResultDto;
use App\Application\Category\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Entity\ValueObject\Id;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        $categories = $this->categoryRepository->findByUserId($userId);
        return $this->getCollectionV1ResultAssembler->assemble($dto, $categories);
    }
}
