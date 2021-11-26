<?php

declare(strict_types=1);


namespace App\Application\Account\Assembler;


use App\Application\Account\Dto\SharedAccessItemResultDto;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountAccessRepositoryInterface;

class AccountIdToSharedAccessResultAssembler
{
    private AccountAccessRepositoryInterface $accountAccessRepository;
    private UserIdToDtoResultAssembler $userIdToDtoResultAssembler;

    public function __construct(AccountAccessRepositoryInterface $accountAccessRepository, UserIdToDtoResultAssembler $userIdToDtoResultAssembler)
    {
        $this->accountAccessRepository = $accountAccessRepository;
        $this->userIdToDtoResultAssembler = $userIdToDtoResultAssembler;
    }

    /**
     * @param Id $accountId
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
