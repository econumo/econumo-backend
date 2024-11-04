<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Type;

use App\EconumoOneBundle\Domain\Entity\ValueObject\ConnectionCode;
use App\EconumoOneBundle\Infrastructure\Doctrine\Type\ReflectionValueObjectTrait;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ConnectionCodeType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return ConnectionCode|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(ConnectionCode::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'connection_code_type';
    }
}
