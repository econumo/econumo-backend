<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Assembler;

use App\EconumoOneBundle\Application\Payee\Assembler\PayeeToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Payee\Dto\CreatePayeeV1RequestDto;
use App\EconumoOneBundle\Application\Payee\Dto\CreatePayeeV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Payee;

class CreatePayeeV1ResultAssembler
{
    public function __construct(private readonly PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        CreatePayeeV1RequestDto $dto,
        Payee $payee
    ): CreatePayeeV1ResultDto {
        $result = new CreatePayeeV1ResultDto();
        $result->item = $this->payeeToDtoV1ResultAssembler->assemble($payee);

        return $result;
    }
}
