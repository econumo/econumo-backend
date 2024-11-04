<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User;

use App\EconumoOneBundle\Application\User\Dto\GetOptionListV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\GetOptionListV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\GetOptionListV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\UserOptionRepositoryInterface;

class OptionListService
{
    public function __construct(private readonly GetOptionListV1ResultAssembler $getOptionListV1ResultAssembler, private readonly UserOptionRepositoryInterface $userOptionRepository)
    {
    }

    public function getOptionList(
        GetOptionListV1RequestDto $dto,
        Id $userId
    ): GetOptionListV1ResultDto {
        $options = $this->userOptionRepository->findByUserId($userId);
        return $this->getOptionListV1ResultAssembler->assemble($dto, $options);
    }
}
