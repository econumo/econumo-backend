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
use App\Domain\Entity\ValueObject\UserPasswordRequestCode;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\UserPasswordNotValidException;
use App\Domain\Exception\UserPasswordRequestExpiredException;
use App\Domain\Service\PasswordUserReminderServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;
use App\Domain\Service\User\UserPasswordServiceInterface;
use App\Application\User\Dto\ResetPasswordV1RequestDto;
use App\Application\User\Dto\ResetPasswordV1ResultDto;
use App\Application\User\Assembler\ResetPasswordV1ResultAssembler;

readonly class PasswordService
{
    public function __construct(
        private RemindPasswordV1ResultAssembler $remindPasswordV1ResultAssembler,
        private UpdatePasswordV1ResultAssembler $updatePasswordV1ResultAssembler,
        private UserPasswordServiceInterface $userPasswordService,
        private TranslationServiceInterface $translationService,
        private ResetPasswordV1ResultAssembler $resetPasswordV1ResultAssembler,
        private PasswordUserReminderServiceInterface $passwordUserReminderService
    ) {
    }

    public function remindPassword(
        RemindPasswordV1RequestDto $dto
    ): RemindPasswordV1ResultDto {
        try {
            $this->passwordUserReminderService->remindPassword(new Email($dto->username));
        } catch (NotFoundException) {
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
        } catch (UserPasswordNotValidException) {
            throw new ValidationException($this->translationService->trans('user.password.not_correct'));
        }

        return $this->updatePasswordV1ResultAssembler->assemble($dto);
    }

    public function resetPassword(
        ResetPasswordV1RequestDto $dto
    ): ResetPasswordV1ResultDto {
        try {
            $this->passwordUserReminderService->resetPassword(new Email($dto->username), new UserPasswordRequestCode($dto->code), $dto->password);
        } catch (UserPasswordRequestExpiredException $e) {
            throw new ValidationException('The code is expired');
        } catch (\Throwable) {
            throw new ValidationException('Reset password error');
        }

        return $this->resetPasswordV1ResultAssembler->assemble($dto);
    }
}
