<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Category;
use App\Domain\Entity\Envelope;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;

interface EnvelopeFactoryInterface
{
    public function create(
        Id $planId,
        EnvelopeType $type,
        Id $currencyId,
        int $position,
        ?Id $folderId,
        ?EnvelopeName $name,
        ?Icon $icon
    ): Envelope;

    public function createFromCategory(
        Id $planId,
        Category $category,
        Id $currencyId,
        int $position,
        ?Id $folderId
    ): Envelope;

    public function createFromTag(
        Id $planId,
        Tag $tag,
        Id $currencyId,
        int $position,
        ?Id $folderId
    ): Envelope;
}
