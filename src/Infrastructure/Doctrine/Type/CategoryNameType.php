<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\CategoryName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CategoryNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(CategoryName::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'category_name_type';
    }
}
