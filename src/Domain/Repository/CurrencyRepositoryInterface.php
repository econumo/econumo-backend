<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface CurrencyRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @throws NotFoundException
     */
    public function get(Id $id): Currency;

    public function getByCode(CurrencyCode $code): ?Currency;

    /**
     * @return Currency[]
     */
    public function getAll(): array;

    public function getReference(Id $id): Currency;

    /**
     * @param Currency[] $items
     */
    public function save(array $items): void;
}
