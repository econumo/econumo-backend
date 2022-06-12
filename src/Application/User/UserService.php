<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Exception\ValidationException;
use App\Application\User\Dto\LoginUserV1ResultDto;
use App\Application\User\Assembler\LoginUserV1ResultAssembler;
use App\Application\User\Dto\LogoutUserV1RequestDto;
use App\Application\User\Dto\LogoutUserV1ResultDto;
use App\Application\User\Assembler\LogoutUserV1ResultAssembler;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Exception\UserRegisteredException;
use App\Domain\Service\Translation\TranslationServiceInterface;
use App\Domain\Service\UserServiceInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Application\User\Dto\RegisterUserV1RequestDto;
use App\Application\User\Dto\RegisterUserV1ResultDto;
use App\Application\User\Assembler\RegisterUserV1ResultAssembler;

class UserService
{
    private RegisterUserV1ResultAssembler $registerUserV1ResultAssembler;
    private LoginUserV1ResultAssembler $loginUserV1ResultAssembler;
    private LogoutUserV1ResultAssembler $logoutUserV1ResultAssembler;
    private JWTTokenManagerInterface $authToken;
    private UserServiceInterface $userService;
    private TranslationServiceInterface $translationService;

    public function __construct(
        LoginUserV1ResultAssembler $loginUserV1ResultAssembler,
        LogoutUserV1ResultAssembler $logoutUserV1ResultAssembler,
        JWTTokenManagerInterface $authToken,
        RegisterUserV1ResultAssembler $registerUserV1ResultAssembler,
        UserServiceInterface $userService,
        TranslationServiceInterface $translationService
    ) {
        $this->loginUserV1ResultAssembler = $loginUserV1ResultAssembler;
        $this->logoutUserV1ResultAssembler = $logoutUserV1ResultAssembler;
        $this->authToken = $authToken;
        $this->registerUserV1ResultAssembler = $registerUserV1ResultAssembler;
        $this->userService = $userService;
        $this->translationService = $translationService;
    }

    public function loginUser(
        UserInterface $user
    ): LoginUserV1ResultDto {
        $token = $this->authToken->create($user);
        return $this->loginUserV1ResultAssembler->assemble($user, $token);
    }

    public function logoutUser(
        string $token
    ): LogoutUserV1ResultDto {
        return $this->logoutUserV1ResultAssembler->assemble(new LogoutUserV1RequestDto());
    }

    public function registerUser(
        RegisterUserV1RequestDto $dto
    ): RegisterUserV1ResultDto {
        try {
            $user = $this->userService->register(new Email($dto->email), $dto->password, $dto->name);
            return $this->registerUserV1ResultAssembler->assemble($dto, $user);
        } catch (UserRegisteredException $exception) {
            throw new ValidationException($this->translationService->trans('user.user.already_exists'), 400, $exception);
        }
    }
}
