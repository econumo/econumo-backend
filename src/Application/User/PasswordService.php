<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Exception\ValidationException;
use App\Application\User\Assembler\RemindPasswordV1ResultAssembler;
use App\Application\User\Dto\RemindPasswordV1RequestDto;
use App\Application\User\Dto\RemindPasswordV1ResultDto;
use App\Application\User\Dto\UpdatePasswordV1RequestDto;
use App\Application\User\Dto\UpdatePasswordV1ResultDto;
use App\Application\User\Assembler\UpdatePasswordV1ResultAssembler;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\UserPasswordNotValidException;
use App\Domain\Service\PasswordUserRequestServiceInterface;
use App\Domain\Service\User\UserPasswordServiceInterface;

class PasswordService
{
    private RemindPasswordV1ResultAssembler $remindPasswordV1ResultAssembler;
    private PasswordUserRequestServiceInterface $passwordUserRequestService;
    private UpdatePasswordV1ResultAssembler $updatePasswordV1ResultAssembler;
    private UserPasswordServiceInterface $userPasswordService;

    public function __construct(
        RemindPasswordV1ResultAssembler $remindPasswordV1ResultAssembler,
        PasswordUserRequestServiceInterface $passwordUserRequestService,
        UpdatePasswordV1ResultAssembler $updatePasswordV1ResultAssembler,
        UserPasswordServiceInterface $userPasswordService
    ) {
        $this->remindPasswordV1ResultAssembler = $remindPasswordV1ResultAssembler;
        $this->passwordUserRequestService = $passwordUserRequestService;
        $this->updatePasswordV1ResultAssembler = $updatePasswordV1ResultAssembler;
        $this->userPasswordService = $userPasswordService;
    }

    public function remindPassword(
        RemindPasswordV1RequestDto $dto
    ): RemindPasswordV1ResultDto {
        try {
            $this->passwordUserRequestService->remindPassword(new Email($dto->username));
        } catch (NotFoundException $exception) {
            // hide error from user
        }
        return $this->remindPasswordV1ResultAssembler->assemble($dto);
    }

    public function updatePassword(
        UpdatePasswordV1RequestDto $dto,
        Id $userId
    ): UpdatePasswordV1ResultDto {
        try {
            $this->userPasswordService->changePassword($userId, $dto->oldPassword, $dto->newPassword);
        } catch (UserPasswordNotValidException $exception) {
            throw new ValidationException('Password is not valid');
        }

        return $this->updatePasswordV1ResultAssembler->assemble($dto);
    }
}
