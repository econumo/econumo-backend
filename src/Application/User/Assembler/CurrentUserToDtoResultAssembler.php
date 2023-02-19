<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\CurrentUserResultDto;
use App\Domain\Entity\User;
use App\Domain\Entity\UserOption;

class CurrentUserToDtoResultAssembler
{
    public function assemble(User $user): CurrentUserResultDto
    {
        $dto = new CurrentUserResultDto();
        $dto->id = $user->getId()->getValue();
        $dto->name = $user->getName();
        $dto->email = $user->getUsername();
        $dto->avatar = $user->getAvatarUrl();

        $options = [];
        foreach ($user->getOptions() as $option) {
            $options[$option->getName()] = $option->getValue();
        }
        $dto->currency = $options[UserOption::CURRENCY] ?? UserOption::DEFAULT_CURRENCY;
        $dto->reportPeriod = ($options[UserOption::REPORT_PERIOD] ?? UserOption::DEFAULT_REPORT_PERIOD);

        return $dto;
    }
}
