<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\RevokeAccessV1ResultDto;

readonly class RevokeAccessV1ResultAssembler
{
    public function assemble(): RevokeAccessV1ResultDto
    {
        return new RevokeAccessV1ResultDto();
    }
}
