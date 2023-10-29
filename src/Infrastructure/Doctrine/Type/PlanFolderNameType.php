<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\PlanFolderName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PlanFolderNameType extends StringType
{
    use ReflectionValueObjectTrait;

    /**
     * @inheritdoc
     * @return PlanFolderName|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $this->getInstance(PlanFolderName::class, $value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'plan_folder_name_type';
    }
}
