<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\EnvelopeResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\EnvelopeRepositoryInterface;

readonly class EnvelopeIdToDtoResultAssembler
{
    public function __construct(
        private EnvelopeToDtoResultAssembler $envelopeToDtoResultAssembler,
        private EnvelopeRepositoryInterface $envelopeRepository
    )
    {
    }

    public function assemble(Id $envelopeId): EnvelopeResultDto
    {
        $envelope = $this->envelopeRepository->get($envelopeId);
        return $this->envelopeToDtoResultAssembler->assemble($envelope);
    }
}
