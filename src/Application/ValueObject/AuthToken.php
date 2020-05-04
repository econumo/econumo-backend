<?php
declare(strict_types=1);

namespace App\Application\ValueObject;

class AuthToken implements \JsonSerializable
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'token' => $this->value,
        ];
    }
}
