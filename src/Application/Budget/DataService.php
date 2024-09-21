<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetDataV1RequestDto;
use App\Application\Budget\Dto\GetDataV1ResultDto;
use App\Application\Budget\Assembler\GetDataV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\Domain\Service\Budget\BudgetServiceInterface;
use DateTimeImmutable;

readonly class DataService
{
    public function __construct(
        private GetDataV1ResultAssembler $getDataV1ResultAssembler,
        private BudgetServiceInterface $budgetService,
        private BudgetAccessServiceInterface $budgetAccessService,
    ) {
    }

    public function getData(
        GetDataV1RequestDto $dto,
        Id $userId
    ): GetDataV1ResultDto {
        $budgetId = new Id($dto->id);
        $period = DateTimeImmutable::createFromFormat('Y-m-01 00:00:00', $dto->period);
        if (!$this->budgetAccessService->canReadBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $dataDto = $this->budgetService->getData($userId, $budgetId, $period);
        return $this->getDataV1ResultAssembler->assemble($dataDto);
    }
}
