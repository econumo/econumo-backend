<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\DeclineAccessV1ResultDto;

readonly class DeclineAccessV1ResultAssembler
{
    public function assemble(): DeclineAccessV1ResultDto {
        return new DeclineAccessV1ResultDto();
    }
}
