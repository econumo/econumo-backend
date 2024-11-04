<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use Ramsey\Uuid\Uuid;

trait NextIdentityTrait
{
    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }
}
