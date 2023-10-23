<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;

class EnvelopePositionDto
{
    public string $id;

    public ?string $folderId = null;

    public int $position;

    public function getId(): Id
    {
        return new Id($this->id);
    }

    public function getFolderId(): Id
    {
        return new Id($this->folderId);
    }
}
