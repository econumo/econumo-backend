<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class PlanDataEnvelopeDto
{
    public Id $id;

    public float $budget;

    public ?float $available = null;
}
