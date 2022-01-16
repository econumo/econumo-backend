<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\ConnectionResultDto;
use App\Application\Connection\Dto\GetConnectionListV1RequestDto;
use App\Application\Connection\Dto\GetConnectionListV1ResultDto;
use App\Application\User\Assembler\UserToDtoResultAssembler;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Connection\ConnectionAccountServiceInterface;

class GetConnectionListV1ResultAssembler
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
     * @param GetConnectionListV1RequestDto $dto
     * @param Id $userId
     * @param AccountAccess[] $sharedWithUserAccounts
     * @param User[] $connectedUsers
     * @return GetConnectionListV1ResultDto
     */
    public function assemble(
        GetConnectionListV1RequestDto $dto,
        Id $userId,
        array $sharedWithUserAccounts,
        iterable $connectedUsers
    ): GetConnectionListV1ResultDto {
        $result = new GetConnectionListV1ResultDto();
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
