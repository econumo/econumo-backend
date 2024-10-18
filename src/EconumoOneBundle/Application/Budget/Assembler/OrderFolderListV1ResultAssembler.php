<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\OrderFolderListV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\OrderFolderListV1ResultDto;

readonly class OrderFolderListV1ResultAssembler
{
    public function assemble(
        OrderFolderListV1RequestDto $dto
    ): OrderFolderListV1ResultDto {
        return new OrderFolderListV1ResultDto();
    }
}
