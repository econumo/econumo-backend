<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\CreateEnvelopeV1RequestDto;
use App\Application\Budget\Dto\CreateEnvelopeV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

readonly class CreateEnvelopeV1ResultAssembler
{
    public function __construct(
        private EnvelopeIdToDtoResultAssembler $envelopeIdToDtoResultAssembler
    ) {
    }

    public function assemble(
        CreateEnvelopeV1RequestDto $dto,
        Id $envelopeId
    ): CreateEnvelopeV1ResultDto {
        $result = new CreateEnvelopeV1ResultDto();
        $result->item = $this->envelopeIdToDtoResultAssembler->assemble($envelopeId);

        return $result;
    }
}
