<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface EnvelopeBudgetFactoryInterface
{
    public function create(Id $envelopeId, DateTimeInterface $period, float $amount): EnvelopeBudget;
}
