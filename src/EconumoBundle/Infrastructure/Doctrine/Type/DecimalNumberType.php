<?php
declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Type;

use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DecimalType;
use ReflectionException;

class DecimalNumberType extends DecimalType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return DecimalNumber|null
     * @throws ReflectionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(DecimalNumber::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'decimal_number_type';
    }
}
