<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Connection\Assembler;

use App\EconumoOneBundle\Application\Connection\Assembler\AccountAccessToDtoResultAssembler;
use App\EconumoOneBundle\Application\Connection\Dto\ConnectionResultDto;
use App\EconumoOneBundle\Application\Connection\Dto\GetConnectionListV1RequestDto;
use App\EconumoOneBundle\Application\Connection\Dto\GetConnectionListV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UserToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\AccountAccess;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class GetConnectionListV1ResultAssembler
{
    public function __construct(private readonly UserToDtoResultAssembler $userToDtoResultAssembler, private readonly AccountAccessToDtoResultAssembler $accountAccessToDtoResultAssembler)
    {
    }

    /**
     * @param AccountAccess[] $receivedAccountAccess
     * @param AccountAccess[] $issuedAccountAccess
     * @param User[] $connectedUsers
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