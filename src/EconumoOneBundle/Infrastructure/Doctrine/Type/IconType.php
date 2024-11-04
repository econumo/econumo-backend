<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Type;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Infrastructure\Doctrine\Type\ReflectionValueObjectTrait;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class IconType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return Icon|null
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
