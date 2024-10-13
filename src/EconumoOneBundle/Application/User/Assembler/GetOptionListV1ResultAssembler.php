<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Dto\GetOptionListV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\GetOptionListV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\OptionToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\UserOption;

class GetOptionListV1ResultAssembler
{
    public function __construct(private readonly OptionToDtoResultAssembler $optionToDtoResultAssembler)
    {
    }

    /**
     * @param GetOptionListV1RequestDto $dto
     * @param UserOption[] $options
     * @return GetOptionListV1ResultDto
     */
    public function assemble(
        GetOptionListV1RequestDto $dto,
        array $options
    ): GetOptionListV1ResultDto {
        $result = new GetOptionListV1ResultDto();
        $result->items = [];
        foreach ($options as $option) {
            $result->items[] = $this->optionToDtoResultAssembler->assemble($option);
        }

        return $result;
    }
}
