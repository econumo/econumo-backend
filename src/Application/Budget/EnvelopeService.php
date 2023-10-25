<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\DeleteEnvelopeV1RequestDto;
use App\Application\Budget\Dto\DeleteEnvelopeV1ResultDto;
use App\Application\Budget\Assembler\DeleteEnvelopeV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\EnvelopeIsNotEmptyException;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Service\Budget\EnvelopeServiceInterface;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Application\Budget\Dto\UpdateEnvelopeV1RequestDto;
use App\Application\Budget\Dto\UpdateEnvelopeV1ResultDto;
use App\Application\Budget\Assembler\UpdateEnvelopeV1ResultAssembler;

readonly class EnvelopeService
{
    public function __construct(
        private DeleteEnvelopeV1ResultAssembler $deleteEnvelopeV1ResultAssembler,
        private PlanAccessServiceInterface $planAccessService,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private EnvelopeServiceInterface $envelopeService,
        private UpdateEnvelopeV1ResultAssembler $updateEnvelopeV1ResultAssembler,
    ) {
    }

    public function deleteEnvelope(
        DeleteEnvelopeV1RequestDto $dto,
        Id $userId
    ): DeleteEnvelopeV1ResultDto {
        $envelopeId = new Id($dto->id);
        $envelope = $this->envelopeRepository->get($envelopeId);
        if (!$this->planAccessService->canDeletePlan($userId, $envelope->getPlan()->getId())) {
            throw new AccessDeniedException();
        }

        try {
            $this->envelopeService->deleteEnvelope($envelopeId);
        } catch (NotFoundException) {
            throw new ValidationException('Envelope not found');
        } catch (EnvelopeIsNotEmptyException) {
            throw new ValidationException('Envelope is not empty');
        }
        return $this->deleteEnvelopeV1ResultAssembler->assemble($dto);
    }

    public function updateEnvelope(
        UpdateEnvelopeV1RequestDto $dto,
        Id $userId
    ): UpdateEnvelopeV1ResultDto {
        $envelopeId = new Id($dto->id);
        $envelope = $this->envelopeRepository->get($envelopeId);
        if (!$this->planAccessService->canDeletePlan($userId, $envelope->getPlan()->getId())) {
            throw new AccessDeniedException();
        }

        $name = new EnvelopeName($dto->name);
        $icon = new Icon($dto->icon);
        $currencyId = new Id($dto->currencyId);
        $categories = array_map(fn (string $id) => new Id($id), $dto->categories);
        $tags = array_map(fn (string $id) => new Id($id), $dto->tags);
        $this->envelopeService->updateEnvelope($envelopeId, $name, $icon, $currencyId, $categories, $tags);
        return $this->updateEnvelopeV1ResultAssembler->assemble($dto, $envelopeId);
    }
}
