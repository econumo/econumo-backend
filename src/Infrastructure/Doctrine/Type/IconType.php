<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\Icon;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class IconType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(Icon::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'icon_type';
    }
}
