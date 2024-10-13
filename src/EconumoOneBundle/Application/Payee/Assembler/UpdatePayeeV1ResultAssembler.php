<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Assembler;

use App\EconumoOneBundle\Application\Payee\Dto\UpdatePayeeV1RequestDto;
use App\EconumoOneBundle\Application\Payee\Dto\UpdatePayeeV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class UpdatePayeeV1ResultAssembler
{
    public function assemble(
        UpdatePayeeV1RequestDto $dto
    ): UpdatePayeeV1ResultDto {
        return new UpdatePayeeV1ResultDto();
    }
}
