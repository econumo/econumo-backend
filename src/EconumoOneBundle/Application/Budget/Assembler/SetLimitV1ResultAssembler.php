<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\SetLimitV1ResultDto;

readonly class SetLimitV1ResultAssembler
{
    public function assemble(): SetLimitV1ResultDto
    {
        return new SetLimitV1ResultDto();
    }
}
