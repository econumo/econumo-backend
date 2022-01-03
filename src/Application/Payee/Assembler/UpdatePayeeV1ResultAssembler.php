<?php

declare(strict_types=1);

namespace App\Application\Payee\Assembler;

use App\Application\Payee\Dto\UpdatePayeeV1RequestDto;
use App\Application\Payee\Dto\UpdatePayeeV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

class UpdatePayeeV1ResultAssembler
{
    private PayeeIdToDtoV1ResultAssembler $payeeIdToDtoV1ResultAssembler;

    public function __construct(PayeeIdToDtoV1ResultAssembler $payeeIdToDtoV1ResultAssembler)
    {
        $this->payeeIdToDtoV1ResultAssembler = $payeeIdToDtoV1ResultAssembler;
    }

    public function assemble(
        UpdatePayeeV1RequestDto $dto
    ): UpdatePayeeV1ResultDto {
        $result = new UpdatePayeeV1ResultDto();
        $result->item = $this->payeeIdToDtoV1ResultAssembler->assemble(new Id($dto->id));

        return $result;
    }
}
