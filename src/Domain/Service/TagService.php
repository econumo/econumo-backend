<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\TagFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;

class TagService implements TagServiceInterface
{
    private TagFactoryInterface $tagFactory;
    private TagRepositoryInterface $tagRepository;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        TagFactoryInterface $tagFactory,
        TagRepositoryInterface $tagRepository,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->tagFactory = $tagFactory;
        $this->tagRepository = $tagRepository;
        $this->accountRepository = $accountRepository;
    }

    public function createTag(Id $userId, string $name): Tag
    {
        $tag = $this->tagFactory->create($userId, $name);
        $this->tagRepository->save($tag);
        return $tag;
    }

    public function createTagForAccount(Id $userId, Id $accountId, string $name): Tag
    {
        $account = $this->accountRepository->get($accountId);
        if ($userId->isEqual($account->getUserId())) {
            return $this->createTag($userId, $name);
        }

        return $this->createTag($account->getUserId(), $name);
    }

    public function updateTag(Id $tagId, string $name, bool $isArchived): void
    {
        $tag = $this->tagRepository->get($tagId);
        $tag->updateName($name);
        if ($isArchived) {
            $tag->archive();
        } else {
            $tag->unarchive();
        }
        $this->tagRepository->save($tag);
    }

    public function orderTags(Id $userId, Id ...$ids): void
    {
        $tags = $this->tagRepository->findByUserId($userId);
        $position = 0;
        $changed = [];
        foreach ($ids as $id) {
            foreach ($tags as $tag) {
                if ($tag->getId()->isEqual($id)) {
                    $tag->updatePosition($position++);
                    $changed[] = $tag;
                    break;
                }
            }
        }

        $this->tagRepository->save(...$changed);
    }
}
