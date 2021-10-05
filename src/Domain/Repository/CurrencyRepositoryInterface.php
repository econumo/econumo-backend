<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface CurrencyRepositoryInterface
{
    /**
     * @param Id $id
     * @return Currency
     * @throws NotFoundException
     */
    public function get(Id $id): Currency;

    /**
     * @return Currency[]
     */
    public function getAll(): array;
}
