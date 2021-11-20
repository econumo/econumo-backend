<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\GetCollectionV1ResultAssembler;
use App\Application\Account\Dto\GetCollectionV1RequestDto;
use App\Application\Account\Dto\GetCollectionV1ResultDto;
use App\Application\Account\Dto\ReorderCollectionV1RequestDto;
use App\Application\Account\Dto\ReorderCollectionV1ResultDto;
use App\Application\Account\Assembler\ReorderCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private AccountRepositoryInterface $accountRepository;
    private ReorderCollectionV1ResultAssembler $reorderCollectionV1ResultAssembler;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        AccountRepositoryInterface $accountRepository,
        ReorderCollectionV1ResultAssembler $reorderCollectionV1ResultAssembler
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->accountRepository = $accountRepository;
        $this->reorderCollectionV1ResultAssembler = $reorderCollectionV1ResultAssembler;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        $accounts = $this->accountRepository->findByUserId($userId);
        return $this->getCollectionV1ResultAssembler->assemble($dto, $userId, $accounts);
    }

    public function reorderCollection(
        ReorderCollectionV1RequestDto $dto,
        Id $userId
    ): ReorderCollectionV1ResultDto {
        // --- dump ---
        echo '<pre>';
        echo __FILE__ . chr(10);
        echo __METHOD__ . chr(10);
        var_dump($dto);
        echo '</pre>';
        exit;
        // --- // ---
        return $this->reorderCollectionV1ResultAssembler->assemble($dto);
    }
}
