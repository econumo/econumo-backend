<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\Category;
use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\EnvelopeIsNotEmptyException;
use App\Domain\Service\Dto\EnvelopePositionDto;
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

    /**
     * @param Id $planId
     * @param EnvelopePositionDto[] $changes
     * @return void
     */
    public function orderEnvelopes(Id $planId, array $changes): void;

    /**
     * @param Id $envelopeId
     * @return void
     * @throws EnvelopeIsNotEmptyException
     */
    public function deleteEnvelope(Id $envelopeId): void;
}
