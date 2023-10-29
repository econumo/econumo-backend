<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\UpdateEnvelopeV1RequestDto;
use App\Application\Budget\Dto\UpdateEnvelopeV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

readonly class UpdateEnvelopeV1ResultAssembler
{
    public function __construct(
        private EnvelopeIdToDtoResultAssembler $envelopeIdToDtoResultAssembler
    ) {
    }

    public function assemble(
        UpdateEnvelopeV1RequestDto $dto,
        Id $envelopeId
    ): UpdateEnvelopeV1ResultDto {
        $result = new UpdateEnvelopeV1ResultDto();
        $result->item = $this->envelopeIdToDtoResultAssembler->assemble($envelopeId);

        return $result;
    }
}
