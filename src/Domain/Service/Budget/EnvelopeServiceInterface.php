<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Category;
use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface EnvelopeServiceInterface
{
    public function createConnectedEnvelopesByCategory(Category $category, Id $userId): void;

    public function createConnectedEnvelopesByTag(Tag $tag, Id $userId): void;

    public function createEnvelopesForUser(Id $planId, Id $userId, Id $currencyId, int &$envelopePosition, Id $folderId): void;

    /**
     * @param Id $planId
     * @param DateTimeInterface $date
     * @return EnvelopeBudget[]
     */
    public function getEnvelopesBudgets(Id $planId, DateTimeInterface $date): array;

    /**
     * @param Id $planId
     * @param DateTimeInterface $date
     * @return array
     */
    public function getEnvelopesAvailable(Id $planId, DateTimeInterface $date): array;

    public function updateEnvelopeBudget(Id $envelopeId, DateTimeInterface $period, float $amount): void;

    public function transferEnvelopeBudget(Id $fromEnvelopeId, Id $toEnvelopeId, DateTimeInterface $period, float $amount): void;
}
