<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TagName;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class TagFactory implements TagFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly TagRepositoryInterface $tagRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function create(Id $userId, TagName $name): Tag
    {
        return new Tag(
            $this->tagRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
