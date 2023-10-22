<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\EnvelopeBudget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\EnvelopeBudgetRepositoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

readonly class EnvelopeBudgetFactory implements EnvelopeBudgetFactoryInterface
{
    public function __construct(
        private EnvelopeBudgetRepositoryInterface $envelopeBudgetRepository,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private DatetimeServiceInterface $datetimeService
    ) {
    }

    public function create(Id $envelopeId, \DateTimeInterface $period, float $amount): EnvelopeBudget
    {
        return new EnvelopeBudget(
            $this->envelopeBudgetRepository->getNextIdentity(),
            $this->envelopeRepository->getReference($envelopeId),
            $amount,
            $period,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
