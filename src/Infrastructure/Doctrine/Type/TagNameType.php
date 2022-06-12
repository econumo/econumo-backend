<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\TagName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class TagNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(TagName::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'tag_name_type';
    }
}
