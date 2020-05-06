<?php
declare(strict_types=1);

namespace App\Application\Category;

use App\Application\Category\Assembler\GetListDisplayAssembler;
use App\Application\Category\Dto\GetListDisplayDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;

class CategoryService
{
    /**
     * @var GetListDisplayAssembler
     */
    private $getListDisplayAssembler;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        GetListDisplayAssembler $getListDisplayAssembler,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->getListDisplayAssembler = $getListDisplayAssembler;
        $this->categoryRepository = $categoryRepository;
    }

    public function getList(Id $id): GetListDisplayDto
    {
        $categories = $this->categoryRepository->findByUserId($id);
        return $this->getListDisplayAssembler->assemble($categories);
    }
}
