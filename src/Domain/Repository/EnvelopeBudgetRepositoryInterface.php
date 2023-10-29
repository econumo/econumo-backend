<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use DateTimeInterface;

interface EnvelopeBudgetRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $envelopeId
     * @param DateTimeInterface $period
     * @return EnvelopeBudget
     * @throws NotFoundException
     */
    public function getByEnvelopeIdAndPeriod(Id $envelopeId, DateTimeInterface $period): EnvelopeBudget;

    /**
     * @return EnvelopeBudget[]
     */
    public function getByPlanIdAndPeriod(Id $planId, DateTimeInterface $period): array;

    public function deleteByPlanId(Id $planId): void;

    /**
     * @param Id $planId
     * @param DateTimeInterface $period
     * @return array
     */
    public function getSumByPlanIdAndPeriod(Id $planId, DateTimeInterface $period): array;

    public function get(Id $id): EnvelopeBudget;

    /**
     * @param EnvelopeBudget[] $items
     */
    public function save(array $items): void;

    public function delete(EnvelopeBudget $item): void;

    public function getReference(Id $id): EnvelopeBudget;
}
