<?php
declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\OptionResultDto;
use App\Domain\Entity\UserOption;

class OptionToDtoResultAssembler
{
    public function assemble(UserOption $option): OptionResultDto
    {
        $dto = new OptionResultDto();
        $dto->name = $option->getName();
        $dto->value = $option->getValue();

        return $dto;
    }
}
