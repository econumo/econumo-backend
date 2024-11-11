<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Budget\Assembler;

use App\EconumoBundle\Application\Budget\Dto\OrderFolderListV1RequestDto;
use App\EconumoBundle\Application\Budget\Dto\OrderFolderListV1ResultDto;

readonly class OrderFolderListV1ResultAssembler
{
    public function assemble(
        OrderFolderListV1RequestDto $dto
    ): OrderFolderListV1ResultDto {
        return new OrderFolderListV1ResultDto();
    }
}
