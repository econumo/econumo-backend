<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\EnvelopeTagResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;

readonly class TagIdsToDtoResultAssembler
{
    public function __construct(
        private TagRepositoryInterface $tagRepository
    ) {
    }

    /**
     * @param Id[] $tagIds
     * @return EnvelopeTagResultDto[]
     */
    public function assemble(Id $envelopeId, array $tagIds): array
    {
        $result = [];
        $tags = $this->tagRepository->getByIds($tagIds);
        foreach ($tags as $tag) {
            $dto = new EnvelopeTagResultDto();
            $dto->id = $tag->getId()->getValue();
            $dto->ownerUserId = $tag->getUserId()->getValue();
            $dto->name = $tag->getName()->getValue();
            $dto->isArchived = $tag->isArchived() ? 1 : 0;
            $dto->envelopeId = $envelopeId->getValue();
            $result[] = $dto;
        }
        return $result;
    }
}
