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
use App\EconumoOneBundle\Application\Budget\Dto\UpdateEnvelopeV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\UpdateEnvelopeV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\UpdateEnvelopeV1ResultAssembler;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteEnvelopeV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteEnvelopeV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\DeleteEnvelopeV1ResultAssembler;

readonly class EnvelopeService
{
    public function __construct(
        private CreateEnvelopeV1ResultAssembler $createEnvelopeV1ResultAssembler,
        private BudgetAccessServiceInterface $budgetAccessService,
        private BudgetEnvelopeServiceInterface $budgetEnvelopeService,
        private UpdateEnvelopeV1ResultAssembler $updateEnvelopeV1ResultAssembler,
        private DeleteEnvelopeV1ResultAssembler $deleteEnvelopeV1ResultAssembler,
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
            new Id($dto->currencyId),
            new BudgetEnvelopeName($dto->name),
            new Icon($dto->icon),
            0,
            false,
            array_map(function (string $id) {
                return new Id($id);
            }, $dto->categories)
        );
        $folderId = $dto->folderId === null ? null : new Id($dto->folderId);
        $element = $this->budgetEnvelopeService->create($budgetId, $envelopeDto, $folderId);
        return $this->createEnvelopeV1ResultAssembler->assemble($element);
    }

    public function updateEnvelope(
        UpdateEnvelopeV1RequestDto $dto,
        Id $userId
    ): UpdateEnvelopeV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canUpdateBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }

        $envelopeDto = new BudgetEnvelopeDto(
            new Id($dto->id),
            new Id($dto->currencyId),
            new BudgetEnvelopeName($dto->name),
            new Icon($dto->icon),
            0,
            (bool)$dto->isArchived,
            array_map(function (string $id) {
                return new Id($id);
            }, $dto->categories)
        );
        $element = $this->budgetEnvelopeService->update($budgetId, $envelopeDto);
        return $this->updateEnvelopeV1ResultAssembler->assemble($element);
    }

    public function deleteEnvelope(
        DeleteEnvelopeV1RequestDto $dto,
        Id $userId
    ): DeleteEnvelopeV1ResultDto {
        $budgetId = new Id($dto->budgetId);
        if (!$this->budgetAccessService->canDeleteBudget($userId, $budgetId)) {
            throw new AccessDeniedException();
        }
        $envelopeId = new Id($dto->id);

        $this->budgetEnvelopeService->delete($budgetId, $envelopeId);
        return $this->deleteEnvelopeV1ResultAssembler->assemble();
    }
}
