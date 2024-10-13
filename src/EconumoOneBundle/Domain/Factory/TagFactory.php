<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\Tag;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\TagName;
use App\EconumoOneBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoOneBundle\Domain\Factory\TagFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

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
