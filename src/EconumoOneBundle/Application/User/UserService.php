<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User;

use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Application\User\Dto\LoginUserV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\LoginUserV1ResultAssembler;
use App\EconumoOneBundle\Application\User\Dto\LogoutUserV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\LogoutUserV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\LogoutUserV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Exception\UserRegisteredException;
use App\EconumoOneBundle\Domain\Exception\UserRegistrationDisabledException;
use App\EconumoOneBundle\Domain\Service\Translation\TranslationServiceInterface;
use App\EconumoOneBundle\Domain\Service\UserServiceInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\EconumoOneBundle\Application\User\Dto\RegisterUserV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\RegisterUserV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\RegisterUserV1ResultAssembler;

class UserService
{
    public function __construct(private readonly LoginUserV1ResultAssembler $loginUserV1ResultAssembler, private readonly LogoutUserV1ResultAssembler $logoutUserV1ResultAssembler, private readonly JWTTokenManagerInterface $authToken, private readonly RegisterUserV1ResultAssembler $registerUserV1ResultAssembler, private readonly UserServiceInterface $userService, private readonly TranslationServiceInterface $translationService)
    {
    }

    public function loginUser(
        User $user
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
        } catch (UserRegisteredException $userRegisteredException) {
            throw new ValidationException($this->translationService->trans('user.user.already_exists'), 400, $userRegisteredException);
        } catch (UserRegistrationDisabledException $userRegistrationDisabledException) {
            throw new ValidationException('Registration disabled', 400, $userRegistrationDisabledException);
        }
    }
}
