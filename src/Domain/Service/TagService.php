<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\TagFactoryInterface;
use App\Domain\Repository\TagRepositoryInterface;

class TagService implements TagServiceInterface
{
    private TagFactoryInterface $tagFactory;
    private TagRepositoryInterface $tagRepository;

    public function __construct(
        TagFactoryInterface $tagFactory,
        TagRepositoryInterface $tagRepository
    ) {
        $this->tagFactory = $tagFactory;
        $this->tagRepository = $tagRepository;
    }

    public function createTag(Id $userId, Id $tagId, string $name): Tag
    {
        $tag = $this->tagFactory->create($userId, $tagId, $name);
        $this->tagRepository->save($tag);
        return $tag;
    }
}
