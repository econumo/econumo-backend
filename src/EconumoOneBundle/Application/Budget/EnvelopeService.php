<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget;

use App\EconumoOneBundle\Application\Budget\Dto\CreateEnvelopeV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\CreateEnvelopeV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\CreateEnvelopeV1ResultAssembler;
use App\EconumoOneBundle\Application\Exception\AccessDeniedException;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetAccessServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\BudgetEnvelopeServiceInterface;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEnvelopeDto;

readonly class EnvelopeService
{
    public function __construct(
        private CreateEnvelopeV1ResultAssembler $createEnvelopeV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private BudgetEnvelopeServiceInterface $budgetEnvelopeService
    ) {
    }

    public function createEnvelope(
        CreateEnvelopeV1RequestDto $dto,
        Id $userId
    ): CreateEnvelopeV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $envelopeDto = new BudgetEnvelopeDto(
            new Id($dto->id),
            ($dto->folderId === null ? null : new Id($dto->folderId)),
            new Id($dto->currencyId),
            new BudgetEnvelopeName($dto->name),
            new Icon($dto->icon),
            0,
            false,
            array_map(function (string $id) {
                return new Id($id);
            }, $dto->categories)
        );
        $element = $this->budgetEnvelopeService->create($budgetId, $envelopeDto);
        return $this->createEnvelopeV1ResultAssembler->assemble($element);
    }
}
