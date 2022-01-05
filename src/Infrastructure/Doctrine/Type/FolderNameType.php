<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Entity\ValueObject\FolderName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class FolderNameType extends StringType
{
    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : new FolderName($value);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'folder_name_type';
    }
}
