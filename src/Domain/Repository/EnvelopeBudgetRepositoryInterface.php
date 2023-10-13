<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface EnvelopeBudgetRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return EnvelopeBudget[]
     */
    public function getByEnvelopeIdAndPeriod(Id $envelopeId, DateTimeInterface $period): array;

    public function get(Id $id): EnvelopeBudget;

    /**
     * @param EnvelopeBudget[] $items
     */
    public function save(array $items): void;

    public function delete(EnvelopeBudget $item): void;

    public function getReference(Id $id): EnvelopeBudget;
}
