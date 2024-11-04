<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class PositionDto
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var int
     */
    public int $position;

    public function getId(): Id
    {
        return new Id($this->id);
    }
}
