<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\AcceptInviteV1RequestDto;
use App\Application\Connection\Dto\AcceptInviteV1ResultDto;
use App\Application\Connection\Dto\ConnectionResultDto;
use App\Application\User\Assembler\UserToDtoResultAssembler;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionAccountServiceInterface;

class AcceptInviteV1ResultAssembler
{
    private UserToDtoResultAssembler $userToDtoResultAssembler;
    private ConnectionAccountServiceInterface $connectionAccountService;
    private AccountAccessToDtoResultAssembler $accountAccessToDtoResultAssembler;

    public function __construct(
        UserToDtoResultAssembler $userToDtoResultAssembler,
        ConnectionAccountServiceInterface $connectionAccountService,
        AccountAccessToDtoResultAssembler $accountAccessToDtoResultAssembler
    ) {
        $this->userToDtoResultAssembler = $userToDtoResultAssembler;
        $this->connectionAccountService = $connectionAccountService;
        $this->accountAccessToDtoResultAssembler = $accountAccessToDtoResultAssembler;
    }

    /**
     * @param AcceptInviteV1RequestDto $dto
     * @param Id $userId
     * @param AccountAccess[] $sharedWithUserAccounts
     * @param User[] $connectedUsers
     * @return AcceptInviteV1ResultDto
     */
    public function assemble(
        AcceptInviteV1RequestDto $dto,
        Id $userId,
        array $sharedWithUserAccounts,
        iterable $connectedUsers
    ): AcceptInviteV1ResultDto {
        $result = new AcceptInviteV1ResultDto();
        $result->items = [];
        foreach ($connectedUsers as $connectedUser) {
            $connectionDto = new ConnectionResultDto();
            $connectionDto->user = $this->userToDtoResultAssembler->assemble($connectedUser);
            $connectionDto->sharedAccounts = [];
            $sharedAccessForConnectedUser = $this->connectionAccountService->getSharedAccess($connectedUser->getId());
            foreach ($sharedAccessForConnectedUser as $accountAccess) {
                if ($accountAccess->getUserId()->isEqual($userId)) {
                    $connectionDto->sharedAccounts[] = $this->accountAccessToDtoResultAssembler->assemble(
                        $accountAccess
                    );
                }
            }
            foreach ($sharedWithUserAccounts as $accountAccess) {
                if ($accountAccess->getAccount()->getUserId()->isEqual($connectedUser->getId())) {
                    $connectionDto->sharedAccounts[] = $this->accountAccessToDtoResultAssembler->assemble(
                        $accountAccess
                    );
                }
            }
            $result->items[] = $connectionDto;
        }

        return $result;
    }
}
