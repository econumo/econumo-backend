<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Dto\CurrentUserResultDto;
use App\EconumoOneBundle\Application\User\Dto\OptionResultDto;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\UserOption;
use App\EconumoOneBundle\Domain\Service\EncodeServiceInterface;

readonly class CurrentUserToDtoResultAssembler
{
    public function __construct(
        private EncodeServiceInterface $encoder,
    ) {
    }


    public function assemble(User $user): CurrentUserResultDto
    {
        $dto = new CurrentUserResultDto();
        $dto->id = $user->getId()->getValue();
        $dto->name = $user->getName();
        $dto->email = $this->encoder->decode($user->getEmail());
        $dto->avatar = $user->getAvatarUrl();

        $dto->options = [];
        foreach ($user->getOptions() as $option) {
            $tmp = new OptionResultDto();
            $tmp->name = $option->getName();
            $tmp->value = $option->getValue();
            $dto->options[] = $tmp;
            $options[$option->getName()] = $option->getValue();
        }
        $dto->currency = $options[UserOption::CURRENCY] ?? UserOption::DEFAULT_CURRENCY;
        $dto->reportPeriod = ($options[UserOption::REPORT_PERIOD] ?? UserOption::DEFAULT_REPORT_PERIOD);

        return $dto;
    }
}
