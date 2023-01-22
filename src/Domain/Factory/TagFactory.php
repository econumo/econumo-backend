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
    private DatetimeServiceInterface $datetimeService;

    private TagRepositoryInterface $tagRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        TagRepositoryInterface $tagRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->datetimeService = $datetimeService;
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;
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
