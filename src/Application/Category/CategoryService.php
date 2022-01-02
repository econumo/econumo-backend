<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Application\Category\Dto\CreateCategoryV1RequestDto;
use App\Application\Category\Dto\CreateCategoryV1ResultDto;
use App\Application\Category\Assembler\CreateCategoryV1ResultAssembler;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\CategoryServiceInterface;

class CategoryService
{
    private CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler;
    private CategoryServiceInterface $categoryService;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler,
        CategoryServiceInterface $categoryService,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->createCategoryV1ResultAssembler = $createCategoryV1ResultAssembler;
        $this->categoryService = $categoryService;
        $this->accountAccessService = $accountAccessService;
    }

    public function createCategory(
        CreateCategoryV1RequestDto $dto,
        Id $userId
    ): CreateCategoryV1ResultDto {
        $icon = new Icon(!empty($dto->icon) ? $dto->icon : 'local_offer');
        if ($dto->accountId !== null) {
            $accountId = new Id($dto->accountId);
            $this->accountAccessService->checkAddCategory($userId, $accountId);
            $category = $this->categoryService->createCategoryForAccount(
                $userId,
                $accountId,
                $dto->name,
                CategoryType::createFromAlias($dto->type),
                $icon
            );
        } else {
            $category = $this->categoryService->createCategory(
                $userId,
                $dto->name,
                CategoryType::createFromAlias($dto->type),
                $icon
            );
        }

        return $this->createCategoryV1ResultAssembler->assemble($dto, $category);
    }
}
