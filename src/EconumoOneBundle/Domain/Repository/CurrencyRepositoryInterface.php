<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\Currency;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;

interface CurrencyRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @throws NotFoundException
     */
    public function get(Id $id): Currency;

    /**
     * @param array $ids
     * @return Currency[]
     */
    public function getByIds(array $ids): array;

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
