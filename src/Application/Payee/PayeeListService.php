<?php

declare(strict_types=1);

namespace App\Application\Payee;

use App\Application\Payee\Dto\GetPayeeListV1RequestDto;
use App\Application\Payee\Dto\GetPayeeListV1ResultDto;
use App\Application\Payee\Assembler\GetPayeeListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PayeeRepositoryInterface;

class PayeeListService
{
    private GetPayeeListV1ResultAssembler $getPayeeListV1ResultAssembler;
    private PayeeRepositoryInterface $payeeRepository;

    public function __construct(
        GetPayeeListV1ResultAssembler $getPayeeListV1ResultAssembler,
        PayeeRepositoryInterface $payeeRepository
    ) {
        $this->getPayeeListV1ResultAssembler = $getPayeeListV1ResultAssembler;
        $this->payeeRepository = $payeeRepository;
    }

    public function getPayeeList(
        GetPayeeListV1RequestDto $dto,
        Id $userId
    ): GetPayeeListV1ResultDto {
        $payees = $this->payeeRepository->findByUserId($userId);
        return $this->getPayeeListV1ResultAssembler->assemble($dto, $payees);
    }
}
