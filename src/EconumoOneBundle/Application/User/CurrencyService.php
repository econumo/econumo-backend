<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User;

use App\EconumoOneBundle\Application\User\Dto\UpdateCurrencyV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\UpdateCurrencyV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UpdateCurrencyV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\UserServiceInterface;

class CurrencyService
{
    public function __construct(private readonly UpdateCurrencyV1ResultAssembler $updateCurrencyV1ResultAssembler, private readonly UserServiceInterface $userService, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function updateCurrency(
        UpdateCurrencyV1RequestDto $dto,
        Id $userId
    ): UpdateCurrencyV1ResultDto {
        $this->userService->updateCurrency($userId, new CurrencyCode($dto->currency));
        $user = $this->userRepository->get($userId);
        return $this->updateCurrencyV1ResultAssembler->assemble($dto, $user);
    }
}
