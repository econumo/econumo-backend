<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Category;
use App\Domain\Entity\Envelope;
use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

readonly class EnvelopeFactory implements EnvelopeFactoryInterface
{
    public function __construct(
        private EnvelopeRepositoryInterface $envelopeRepository,
        private DatetimeServiceInterface $datetimeService,
        private PlanRepositoryInterface $planRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private PlanFolderRepositoryInterface $planFolderRepository
    ) {
    }

    public function create(
        Id $planId,
        EnvelopeType $type,
        Id $currencyId,
        int $position,
        ?Id $folderId,
        ?EnvelopeName $name,
        ?Icon $icon
    ): Envelope {
        return new Envelope(
            $this->envelopeRepository->getNextIdentity(),
            $this->planRepository->getReference($planId),
            $this->currencyRepository->getReference($currencyId),
            ($folderId === null ? null : $this->planFolderRepository->getReference($folderId)),
            $type,
            $position,
            $name,
            $icon,
            $this->datetimeService->getCurrentDatetime()
        );
    }

    public function createFromCategory(
        Id $planId,
        Category $category,
        Id $currencyId,
        int $position,
        ?Id $folderId
    ): Envelope {
        $envelope = new Envelope(
            $this->envelopeRepository->getNextIdentity(),
            $this->planRepository->getReference($planId),
            $this->currencyRepository->getReference($currencyId),
            ($folderId === null ? null : $this->planFolderRepository->getReference($folderId)),
            ($category->getType()->isExpense() ? EnvelopeType::createExpense() : EnvelopeType::createIncome()),
            $position,
            null,
            null,
            $this->datetimeService->getCurrentDatetime()
        );
        $envelope->addCategory($category);
        return $envelope;
    }

    public function createFromTag(Id $planId, Tag $tag, Id $currencyId, int $position, ?Id $folderId): Envelope
    {
        $envelope = new Envelope(
            $this->envelopeRepository->getNextIdentity(),
            $this->planRepository->getReference($planId),
            $this->currencyRepository->getReference($currencyId),
            ($folderId === null ? null : $this->planFolderRepository->getReference($folderId)),
            EnvelopeType::createExpense(),
            $position,
            null,
            null,
            $this->datetimeService->getCurrentDatetime()
        );
        $envelope->addTag($tag);
        return $envelope;
    }
}
