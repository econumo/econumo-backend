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

class GetConnectionListV1ResultAssembler
{
    private UserToDtoResultAssembler $userToDtoResultAssembler;

    private AccountAccessToDtoResultAssembler $accountAccessToDtoResultAssembler;

    public function __construct(
        UserToDtoResultAssembler $userToDtoResultAssembler,
        AccountAccessToDtoResultAssembler $accountAccessToDtoResultAssembler
    ) {
        $this->userToDtoResultAssembler = $userToDtoResultAssembler;
        $this->accountAccessToDtoResultAssembler = $accountAccessToDtoResultAssembler;
    }

    /**
     * @param GetConnectionListV1RequestDto $dto
     * @param Id $userId
     * @param AccountAccess[] $receivedAccountAccess
     * @param AccountAccess[] $issuedAccountAccess
     * @param User[] $connectedUsers
     * @return GetConnectionListV1ResultDto
     */
    public function assemble(
        GetConnectionListV1RequestDto $dto,
        Id $userId,
        array $receivedAccountAccess,
        array $issuedAccountAccess,
        iterable $connectedUsers
    ): GetConnectionListV1ResultDto {
        $result = new GetConnectionListV1ResultDto();
        $result->items = [];
        foreach ($connectedUsers as $connectedUser) {
            $connectionDto = new ConnectionResultDto();
            $connectionDto->user = $this->userToDtoResultAssembler->assemble($connectedUser);
            $connectionDto->sharedAccounts = [];
            $sharedAccounts = [];
            foreach ($receivedAccountAccess as $accountAccess) {
                $key = $accountAccess->getAccountId()->getValue();
                if ($accountAccess->getAccount()->getUserId()->isEqual($connectedUser->getId())) {
                    $sharedAccounts[$key] = $this->accountAccessToDtoResultAssembler->assemble(
                        $accountAccess
                    );
                } elseif ($accountAccess->getAccount()->getUserId()->isEqual($userId)) {
                    $sharedAccounts[$key] = $this->accountAccessToDtoResultAssembler->assemble(
                        $accountAccess
                    );
                }
            }

            foreach ($issuedAccountAccess as $accountAccess) {
                $key = $accountAccess->getAccountId()->getValue();
                if ($accountAccess->getAccount()->getUserId()->isEqual($connectedUser->getId())) {
                    $sharedAccounts[$key] = $this->accountAccessToDtoResultAssembler->assemble(
                        $accountAccess
                    );
                } elseif ($accountAccess->getAccount()->getUserId()->isEqual($userId)) {
                    $sharedAccounts[$key] = $this->accountAccessToDtoResultAssembler->assemble(
                        $accountAccess
                    );
                }
            }

            $connectionDto->sharedAccounts = array_values($sharedAccounts);
            $result->items[] = $connectionDto;
        }

        return $result;
    }
}
