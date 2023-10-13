<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use DateTimeInterface;

class PlanDto
{
    public Id $id;

    public PlanName $name;

    public Id $ownerUserId;

    public DateTimeInterface $createdAt;

    public DateTimeInterface $updatedAt;

    /**
     * @var Id[] currency id's
     */
    public array $currencies = [];

    /**
     * @var Id[] folder id's
     */
    public array $folders = [];

    /**
     * @var Id[] envelope id's
     */
    public array $envelopes = [];

    /**
     * @var Id[][] categories to envelopes mapping
     */
    public array $categories = [];

    /**
     * @var Id[][] tags to envelopes mapping
     */
    public array $tags = [];

    /**
     * @var Id[] user id's
     */
    public array $sharedAccess = [];
}
