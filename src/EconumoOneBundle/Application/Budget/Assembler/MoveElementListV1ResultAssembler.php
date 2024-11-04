<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\MoveElementListV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\MoveElementListV1ResultDto;

readonly class MoveElementListV1ResultAssembler
{
    public function assemble(
        MoveElementListV1RequestDto $dto
    ): MoveElementListV1ResultDto {
        return new MoveElementListV1ResultDto();
    }
}
