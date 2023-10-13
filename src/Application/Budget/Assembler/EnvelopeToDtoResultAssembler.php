<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\EnvelopeResultDto;
use App\Domain\Entity\Envelope;

readonly class EnvelopeToDtoResultAssembler
{
    public function assemble(Envelope $envelope): EnvelopeResultDto
    {
        $dto = new EnvelopeResultDto();
        $dto->id = $envelope->getId()->getValue();
        $dto->name = $envelope->getName()->getValue();
        $dto->icon = $envelope->getIcon()->getValue();
        $dto->type = $envelope->getType()->getAlias();
        $dto->currencyId = $envelope->getCurrency()->getId()->getValue();
        $dto->folderId = null;
        if ($envelope->getFolder() !== null) {
            $dto->folderId = $envelope->getFolder()->getId()->getValue();
        }
        $dto->position = $envelope->getPosition();
        $dto->isArchived = $envelope->isArchived() ? 1 : 0;

        return $dto;
    }
}
