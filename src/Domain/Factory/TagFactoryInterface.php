<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TagName;

interface TagFactoryInterface
{
    public function create(Id $userId, TagName $name): Tag;
}
