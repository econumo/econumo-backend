<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\RegisterUserV1RequestDto;
use App\Application\User\Dto\RegisterUserV1ResultDto;
use App\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class RegisterUserV1ResultAssembler
{
    public function __construct(private readonly JWTTokenManagerInterface $authToken)
    {
    }

    public function assemble(
        RegisterUserV1RequestDto $dto,
        User $user
    ): RegisterUserV1ResultDto {
        $result = new RegisterUserV1ResultDto();
        $result->id = $user->getId()->getValue();
        $result->token = $this->authToken->create($user);

        return $result;
    }
}
