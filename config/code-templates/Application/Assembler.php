<?php
declare(strict_types=1);

namespace App\Application\__CG_API_SUBJECT_CC__\Assembler;

use App\Application\__CG_API_SUBJECT_CC__\Dto\__CG_API_ACTION_CC__DisplayDto;

class __CG_API_ACTION_CC__DisplayAssembler
{
    public function assemble(string $id): __CG_API_ACTION_CC__DisplayDto
    {
        $dto = new __CG_API_ACTION_CC__DisplayDto();
        $dto->id = $id;
        $dto->name = 'test';

        return $dto;
    }
}
