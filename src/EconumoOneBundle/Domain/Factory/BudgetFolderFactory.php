<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetFolder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetFolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

readonly class BudgetFolderFactory implements BudgetFolderFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private BudgetRepositoryInterface $budgetRepository
    ) {
    }

    public function create(Id $budgetId, Id $folderId, BudgetFolderName $name): BudgetFolder
    {
        return new BudgetFolder(
            $folderId,
            $this->budgetRepository->getReference($budgetId),
            $name,
            0,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
