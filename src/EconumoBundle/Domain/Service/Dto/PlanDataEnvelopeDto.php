<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

class PlanDataEnvelopeDto
{
    public Id $id;

    public float $budget;

    public ?float $available = null;
}
