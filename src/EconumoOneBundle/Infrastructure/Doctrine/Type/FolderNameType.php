<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Type;

use App\EconumoOneBundle\Domain\Entity\ValueObject\FolderName;
use App\EconumoOneBundle\Infrastructure\Doctrine\Type\ReflectionValueObjectTrait;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class FolderNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return FolderName|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(FolderName::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'folder_name_type';
    }
}
