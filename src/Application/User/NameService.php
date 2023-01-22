<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\UpdateNameV1RequestDto;
use App\Application\User\Dto\UpdateNameV1ResultDto;
use App\Application\User\Assembler\UpdateNameV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\UserServiceInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class NameService
{
    private UpdateNameV1ResultAssembler $updateNameV1ResultAssembler;

    private UserServiceInterface $userService;

    private JWTTokenManagerInterface $authToken;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        UpdateNameV1ResultAssembler $updateNameV1ResultAssembler,
        UserServiceInterface $userService,
        JWTTokenManagerInterface $authToken,
        UserRepositoryInterface $userRepository
    ) {
        $this->updateNameV1ResultAssembler = $updateNameV1ResultAssembler;
        $this->userService = $userService;
        $this->authToken = $authToken;
        $this->userRepository = $userRepository;
    }

    public function updateName(
        UpdateNameV1RequestDto $dto,
        Id $userId
    ): UpdateNameV1ResultDto {
        $this->userService->updateName($userId, $dto->name);
        $user = $this->userRepository->get($userId);
        $token = $this->authToken->create($user);
        return $this->updateNameV1ResultAssembler->assemble($dto, $token);
    }
}
