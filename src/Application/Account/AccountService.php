<?php
declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\GetListDisplayAssembler;
use App\Application\Account\Dto\GetListDisplayDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class AccountService
{
    /**
     * @var GetListDisplayAssembler
     */
    private $getListDisplayAssembler;
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    public function __construct(
        GetListDisplayAssembler $getListDisplayAssembler,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->getListDisplayAssembler = $getListDisplayAssembler;
        $this->accountRepository = $accountRepository;
    }

    public function getList(Id $id): GetListDisplayDto
    {
        $accounts = $this->accountRepository->findByUserId($id);

        return $this->getListDisplayAssembler->assemble($accounts);
    }
}
