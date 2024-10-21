<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEnvelopeDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

interface BudgetEnvelopeServiceInterface
{
    public function create(Id $budgetId, BudgetEnvelopeDto $envelope): BudgetStructureParentElementDto;
}
