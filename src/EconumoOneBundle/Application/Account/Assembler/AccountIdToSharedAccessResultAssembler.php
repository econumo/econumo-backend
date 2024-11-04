<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Account\Assembler;


use App\EconumoOneBundle\Application\Account\Dto\SharedAccessItemResultDto;
use App\EconumoOneBundle\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountAccessRepositoryInterface;

class AccountIdToSharedAccessResultAssembler
{
    public function __construct(private readonly AccountAccessRepositoryInterface $accountAccessRepository, private readonly UserIdToDtoResultAssembler $userIdToDtoResultAssembler)
    {
    }

    /**
     * @return SharedAccessItemResultDto[]
     */
    public function assemble(Id $accountId): array
    {
        $result = [];
        $accessList = $this->accountAccessRepository->getByAccount($accountId);
        foreach ($accessList as $access) {
            $sharedAccess = new SharedAccessItemResultDto();
            $sharedAccess->user = $this->userIdToDtoResultAssembler->assemble($access->getUserId());
            $sharedAccess->role = $access->getRole()->getAlias();
            $result[] = $sharedAccess;
        }

        return $result;
    }
}
