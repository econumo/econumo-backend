<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Type;

use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryName;
use App\EconumoOneBundle\Infrastructure\Doctrine\Type\ReflectionValueObjectTrait;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CategoryNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return CategoryName|null
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
