<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\UserOptionException;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class UserOption
{
    /**
     * @var string
     */
    public const CURRENCY = 'currency';

    /**
     * @var string
     */
    public const DEFAULT_CURRENCY = 'USD';

    /**
     * @var string
     */
    public const REPORT_DAY = 'report_day';

    /**
     * @var string
     */
    public const DEFAULT_REPORT_DAY = '1';


    /**
     * @var string[]
     */
    private const OPTIONS = [
        self::CURRENCY,
        self::REPORT_DAY
    ];

    private Id $id;

    private string $name;

    private ?string $value;

    private User $user;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $id,
        User $user,
        string $name,
        ?string $value,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->checkName($name);
        $this->value = $value;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function checkName(string $name): void
    {
        if (!in_array($name, self::OPTIONS, true)) {
            throw new UserOptionException();
        }
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
