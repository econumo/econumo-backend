<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Assembler;

use App\EconumoOneBundle\Application\Payee\Assembler\PayeeToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Payee\Dto\GetPayeeListV1RequestDto;
use App\EconumoOneBundle\Application\Payee\Dto\GetPayeeListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Payee;

class GetPayeeListV1ResultAssembler
{
    public function __construct(private readonly PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler)
    {
    }

    /**
     * @param Payee[] $payees
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
