<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\RemindPasswordV1RequestDto;
use App\Application\User\Dto\RemindPasswordV1ResultDto;
use App\Application\User\Assembler\RemindPasswordV1ResultAssembler;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Exception\NotFoundException;
use App\Domain\Service\PasswordUserRequestServiceInterface;

class PasswordService
{
    private RemindPasswordV1ResultAssembler $remindPasswordV1ResultAssembler;
    private PasswordUserRequestServiceInterface $passwordUserRequestService;

    public function __construct(
        RemindPasswordV1ResultAssembler $remindPasswordV1ResultAssembler,
        PasswordUserRequestServiceInterface $passwordUserRequestService
    ) {
        $this->remindPasswordV1ResultAssembler = $remindPasswordV1ResultAssembler;
        $this->passwordUserRequestService = $passwordUserRequestService;
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
}
