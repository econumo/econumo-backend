<?php

declare(strict_types=1);

namespace App\Application\Payee\Collection\Assembler;

use App\Application\Payee\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Payee\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Payee\Collection\Dto\PayeeResultDto;
use App\Domain\Entity\Payee;

class GetCollectionV1ResultAssembler
{
    /**
     * @param GetCollectionV1RequestDto $dto
     * @param Payee[] $payees
     * @return GetCollectionV1ResultDto
     */
    public function assemble(
        GetCollectionV1RequestDto $dto,
        array $payees
    ): GetCollectionV1ResultDto {
        $result = new GetCollectionV1ResultDto();
        $result->items = [];
        foreach ($payees as $payee) {
            $item = new PayeeResultDto();
            $item->id = $payee->getId()->getValue();
            $item->name = $payee->getName();
            $item->position = $payee->getPosition();
            $item->ownerId = $payee->getUserId()->getValue();
            $result->items[] = $item;
        }

        return $result;
    }
}
