<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PositionDto;

interface TagServiceInterface
{
    public function createTag(Id $userId, string $name): Tag;

    public function createTagForAccount(Id $userId, Id $accountId, string $name): Tag;

    public function updateTag(Id $tagId, string $name): void;

    public function orderTags(Id $userId, PositionDto ...$changes): void;

    public function deleteTag(Id $tagId): void;

    public function archiveTag(Id $tagId): void;

    public function unarchiveTag(Id $tagId): void;
}
