<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Assembler\FolderToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Account\Dto\OrderFolderListV1RequestDto;
use App\EconumoOneBundle\Application\Account\Dto\OrderFolderListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;

class OrderFolderListV1ResultAssembler
{
    public function __construct(private readonly FolderRepositoryInterface $folderRepository, private readonly FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        OrderFolderListV1RequestDto $dto,
        Id $userId
    ): OrderFolderListV1ResultDto {
        $result = new OrderFolderListV1ResultDto();
        $folders = $this->folderRepository->getByUserId($userId);
        $result->items = [];
        foreach ($folders as $folder) {
            $result->items[] = $this->folderToDtoV1ResultAssembler->assemble($folder);
        }

        return $result;
    }
}
