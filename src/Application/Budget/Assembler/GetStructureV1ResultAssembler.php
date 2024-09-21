<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetStructureV1RequestDto;
use App\Application\Budget\Dto\GetStructureV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;

readonly class GetStructureV1ResultAssembler
{
    public function __construct(
        private BudgetDtoToResultDtoAssembler $budgetDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        Id $userId,
        BudgetStructureDto $budgetDto
    ): GetStructureV1ResultDto {
        $result = new GetStructureV1ResultDto();
        $result->item = $this->budgetDtoToResultDtoAssembler->assemble($userId, $budgetDto);

        return $result;
    }
}
