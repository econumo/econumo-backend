<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\ChangeElementCurrencyV1ResultDto;

readonly class ChangeElementCurrencyV1ResultAssembler
{
    public function assemble(): ChangeElementCurrencyV1ResultDto {
        return new ChangeElementCurrencyV1ResultDto();
    }
}
