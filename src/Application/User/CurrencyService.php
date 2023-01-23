<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\UpdateCurrencyV1RequestDto;
use App\Application\User\Dto\UpdateCurrencyV1ResultDto;
use App\Application\User\Assembler\UpdateCurrencyV1ResultAssembler;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\UserServiceInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class CurrencyService
{
    public function __construct(private readonly UpdateCurrencyV1ResultAssembler $updateCurrencyV1ResultAssembler, private readonly UserServiceInterface $userService, private readonly JWTTokenManagerInterface $authToken, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function updateCurrency(
        UpdateCurrencyV1RequestDto $dto,
        Id $userId
    ): UpdateCurrencyV1ResultDto {
        $this->userService->updateCurrency($userId, new CurrencyCode($dto->currency));
        $user = $this->userRepository->get($userId);
        $token = $this->authToken->create($user);
        return $this->updateCurrencyV1ResultAssembler->assemble($dto, $token);
    }
}
