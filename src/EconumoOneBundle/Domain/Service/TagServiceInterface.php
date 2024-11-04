<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service;


use App\EconumoOneBundle\Domain\Entity\Tag;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\TagName;
use App\EconumoOneBundle\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

interface TagServiceInterface
{
    public function createTag(Id $userId, TagName $name): Tag;

    public function createTagForAccount(Id $userId, Id $accountId, TagName $name): Tag;

    public function updateTag(Id $tagId, TagName $name): void;

    /**
     * @param Id $userId
     * @param PositionDto[] $changes
     * @return void
     */
    public function orderTags(Id $userId, array $changes): void;

    public function deleteTag(Id $tagId): void;

    public function archiveTag(Id $tagId): void;

    public function unarchiveTag(Id $tagId): void;
}
