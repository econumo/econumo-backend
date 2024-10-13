<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection\Assembler;

use App\EconumoOneBundle\Application\Connection\Dto\AcceptInviteV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Assembler\AccountAccessToDtoResultAssembler;
use App\EconumoOneBundle\Application\Connection\Dto\AcceptInviteV1ResultDto;
use App\EconumoOneBundle\Application\Connection\Dto\ConnectionResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UserToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\AccountAccess;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionAccountServiceInterface;

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
