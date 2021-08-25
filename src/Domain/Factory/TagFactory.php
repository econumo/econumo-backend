<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;

class TagFactory implements TagFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $userId, Id $tagId, string $name): Tag
    {
        return new Tag($tagId, $userId, $name, $this->datetimeService->getCurrentDatetime());
    }
}
