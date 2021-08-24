<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;

class CategoryFactory implements CategoryFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $userId, Id $id, string $name, CategoryType $type): Category
    {
        return new Category($id, $userId, $name, $type, $this->datetimeService->getCurrentDatetime());
    }
}
