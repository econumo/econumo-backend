<?php

declare(strict_types=1);

namespace App\Application\Payee\Assembler;

use App\Application\Payee\Dto\PayeeResultDto;
use App\Domain\Entity\Payee;

class PayeeToDtoV1ResultAssembler
{
    public function assemble(
        Payee $payee
    ): PayeeResultDto {
        $item = new PayeeResultDto();
        $item->id = $payee->getId()->getValue();
        $item->name = $payee->getName();
        $item->position = $payee->getPosition();
        $item->ownerUserId = $payee->getUserId()->getValue();
        $item->isArchived = $payee->isArchived() ? 1 : 0;
        $item->createdAt = $payee->getCreatedAt()->format('Y-m-d H:i:s');
        $item->updatedAt = $payee->getUpdatedAt()->format('Y-m-d H:i:s');
        return $item;
    }
}
