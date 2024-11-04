<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\DeleteEnvelopeV1ResultDto;

readonly class DeleteEnvelopeV1ResultAssembler
{
    public function assemble(): DeleteEnvelopeV1ResultDto
    {
        return new DeleteEnvelopeV1ResultDto();
    }
}
