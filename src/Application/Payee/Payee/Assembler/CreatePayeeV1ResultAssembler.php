<?php

declare(strict_types=1);

namespace App\Application\Payee\Payee\Assembler;

use App\Application\Payee\Collection\Assembler\PayeeToDtoV1ResultAssembler;
use App\Application\Payee\Payee\Dto\CreatePayeeV1RequestDto;
use App\Application\Payee\Payee\Dto\CreatePayeeV1ResultDto;
use App\Domain\Entity\Payee;

class CreatePayeeV1ResultAssembler
{
    private PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler;

    public function __construct(PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
        $this->payeeToDtoV1ResultAssembler = $payeeToDtoV1ResultAssembler;
    }

    public function assemble(
        CreatePayeeV1RequestDto $dto,
        Payee $payee
    ): CreatePayeeV1ResultDto {
        $result = new CreatePayeeV1ResultDto();
        $result->payee = $this->payeeToDtoV1ResultAssembler->assemble($payee);

        return $result;
    }
}
