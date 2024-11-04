<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Assembler;

use App\EconumoOneBundle\Application\Tag\Dto\UpdateTagV1RequestDto;
use App\EconumoOneBundle\Application\Tag\Dto\UpdateTagV1ResultDto;
use App\EconumoOneBundle\Application\Tag\Assembler\TagIdToUserTagDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class UpdateTagV1ResultAssembler
{
    public function __construct(private readonly TagIdToUserTagDtoResultAssembler $tagIdToDtoResultAssembler)
    {
    }

    public function assemble(
        UpdateTagV1RequestDto $dto
    ): UpdateTagV1ResultDto {
        $result = new UpdateTagV1ResultDto();
        $result->item = $this->tagIdToDtoResultAssembler->assemble(new Id($dto->id));

        return $result;
    }
}
