<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Category;
use App\Domain\Entity\Envelope;
use App\Domain\Entity\PlanFolder;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;

interface EnvelopeFolderFactoryInterface
{
    public function create(
        Id $planId,
        PlanFolderName $name,
        int $position
    ): PlanFolder;
}
