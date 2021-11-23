<?php

declare(strict_types=1);

namespace App\Application\Payee\Assembler;

use App\Application\Payee\Dto\GetPayeeListV1RequestDto;
use App\Application\Payee\Dto\GetPayeeListV1ResultDto;
use App\Domain\Entity\Payee;

class GetPayeeListV1ResultAssembler
{
    private PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler;

    public function __construct(PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
        $this->payeeToDtoV1ResultAssembler = $payeeToDtoV1ResultAssembler;
    }

    /**
     * @param GetPayeeListV1RequestDto $dto
     * @param Payee[] $payees
     * @return GetPayeeListV1ResultDto
     */
    public function assemble(
        GetPayeeListV1RequestDto $dto,
        array $payees
    ): GetPayeeListV1ResultDto {
        $result = new GetPayeeListV1ResultDto();
        $result->items = [];
        foreach ($payees as $payee) {
            $result->items[] = $this->payeeToDtoV1ResultAssembler->assemble($payee);
        }

        return $result;
    }
}
