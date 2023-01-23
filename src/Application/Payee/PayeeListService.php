<?php

declare(strict_types=1);

namespace App\Application\Payee;

use App\Application\Exception\ValidationException;
use App\Application\Payee\Assembler\GetPayeeListV1ResultAssembler;
use App\Application\Payee\Dto\GetPayeeListV1RequestDto;
use App\Application\Payee\Dto\GetPayeeListV1ResultDto;
use App\Application\Payee\Dto\OrderPayeeListV1RequestDto;
use App\Application\Payee\Dto\OrderPayeeListV1ResultDto;
use App\Application\Payee\Assembler\OrderPayeeListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Service\PayeeServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

class PayeeListService
{
    public function __construct(private readonly GetPayeeListV1ResultAssembler $getPayeeListV1ResultAssembler, private readonly PayeeRepositoryInterface $payeeRepository, private readonly OrderPayeeListV1ResultAssembler $orderPayeeListV1ResultAssembler, private readonly PayeeServiceInterface $payeeService, private readonly TranslationServiceInterface $translationService)
    {
    }

    public function getPayeeList(
        GetPayeeListV1RequestDto $dto,
        Id $userId
    ): GetPayeeListV1ResultDto {
        $payees = $this->payeeRepository->findAvailableForUserId($userId);
        return $this->getPayeeListV1ResultAssembler->assemble($dto, $payees);
    }

    public function orderPayeeList(
        OrderPayeeListV1RequestDto $dto,
        Id $userId
    ): OrderPayeeListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('payee.payee_list.empty_list'));
        }

        $this->payeeService->orderPayees($userId, $dto->changes);
        return $this->orderPayeeListV1ResultAssembler->assemble($dto, $userId);
    }
}
