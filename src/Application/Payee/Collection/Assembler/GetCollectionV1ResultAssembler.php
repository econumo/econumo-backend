<?php

declare(strict_types=1);

namespace App\Application\Payee\Collection\Assembler;

use App\Application\Payee\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Payee\Collection\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Payee;

class GetCollectionV1ResultAssembler
{
    private PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler;

    public function __construct(PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
        $this->payeeToDtoV1ResultAssembler = $payeeToDtoV1ResultAssembler;
    }

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
            $result->items[] = $this->payeeToDtoV1ResultAssembler->assemble($payee);
        }

        return $result;
    }
}
