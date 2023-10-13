<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\EnvelopeType as ValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;

class EnvelopeType extends SmallIntType
{
    /**
     * @inheritdoc
     * @return ValueObject|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : new ValueObject((int)$value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'envelope_type';
    }
}
