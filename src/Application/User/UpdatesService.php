<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\CheckUpdatesV1RequestDto;
use App\Application\User\Dto\CheckUpdatesV1ResultDto;
use App\Application\User\Assembler\CheckUpdatesV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use DateTimeImmutable;

class UpdatesService
{
    private CheckUpdatesV1ResultAssembler $checkUpdatesV1ResultAssembler;

    public function __construct(
        CheckUpdatesV1ResultAssembler $checkUpdatesV1ResultAssembler
    ) {
        $this->checkUpdatesV1ResultAssembler = $checkUpdatesV1ResultAssembler;
    }

    /**
     * @throws \Exception
     */
    public function checkUpdates(
        CheckUpdatesV1RequestDto $dto,
        Id $userId
    ): CheckUpdatesV1ResultDto {
        $lastUpdate = new DateTimeImmutable($dto->lastUpdate);
        return $this->checkUpdatesV1ResultAssembler->assemble($dto);
    }
}
