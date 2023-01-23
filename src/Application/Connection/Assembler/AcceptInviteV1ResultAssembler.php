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
    public function __construct(private readonly UserToDtoResultAssembler $userToDtoResultAssembler, private readonly ConnectionAccountServiceInterface $connectionAccountService, private readonly AccountAccessToDtoResultAssembler $accountAccessToDtoResultAssembler)
    {
    }

    /**
     * @param AccountAccess[] $sharedWithUserAccounts
     * @param User[] $connectedUsers
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
            $sharedAccessForConnectedUser = $this->connectionAccountService->getReceivedAccountAccess($connectedUser->getId());
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
