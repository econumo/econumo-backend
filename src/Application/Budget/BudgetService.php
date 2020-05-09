<?php
declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetListDisplayDto;
use App\Application\Budget\Assembler\GetListDisplayAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetRepositoryInterface;

class BudgetService
{
    /**
     * @var GetListDisplayAssembler
     */
    private $getListDisplayAssembler;
    /**
     * @var BudgetRepositoryInterface
     */
    private $budgetRepository;

    public function __construct(
        GetListDisplayAssembler $getListDisplayAssembler,
        BudgetRepositoryInterface $budgetRepository
    ) {
        $this->getListDisplayAssembler = $getListDisplayAssembler;
        $this->budgetRepository = $budgetRepository;
    }

    public function getList(Id $userId): GetListDisplayDto
    {
        $budgets = $this->budgetRepository->findByUserId($userId);
        return $this->getListDisplayAssembler->assemble($budgets);
    }
}
