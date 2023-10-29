<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Envelope;
use App\Domain\Entity\ValueObject\Id;

interface EnvelopeRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Envelope[]
     */
    public function getByPlanId(Id $planId): array;

    /**
     * @return Envelope[]
     */
    public function getByCategoryId(Id $categoryId): array;

    /**
     * @return Envelope[]
     */
    public function getByTagId(Id $tagId): array;

    public function get(Id $id): Envelope;

    /**
     * @param Envelope[] $items
     */
    public function save(array $items): void;

    public function delete(Envelope $item): void;

    public function getReference(Id $id): Envelope;
}
