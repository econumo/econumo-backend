<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetDataV1RequestDto;
use App\Application\Budget\Dto\GetDataV1ResultDto;
use App\Domain\Service\Dto\PlanDataDto;

readonly class GetDataV1ResultAssembler
{
    public function __construct(
        private PlanDataToResultDtoAssembler $planDataToResultDtoAssembler
    ) {
    }

    /**
     * @param GetDataV1RequestDto $dto
     * @param PlanDataDto[] $data
     * @return GetDataV1ResultDto
     */
    public function assemble(
        GetDataV1RequestDto $dto,
        array $data
    ): GetDataV1ResultDto {
        $result = new GetDataV1ResultDto();
        $result->items = [];
        foreach ($data as $item) {
            $result->items[] = $this->planDataToResultDtoAssembler->assemble($item);
        }

        return $result;
    }
}
