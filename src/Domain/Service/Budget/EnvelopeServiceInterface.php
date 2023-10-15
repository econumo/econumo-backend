<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Category;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;

interface EnvelopeServiceInterface
{
    public function createConnectedEnvelopesByCategory(Category $category, Id $userId): void;

    public function createConnectedEnvelopesByTag(Tag $tag, Id $userId): void;

    public function createEnvelopesForUser(Id $planId, Id $userId, Id $currencyId, int &$envelopePosition, Id $folderId): void;
}
