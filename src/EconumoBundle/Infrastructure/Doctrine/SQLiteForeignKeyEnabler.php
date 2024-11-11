<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine;

use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Platforms\SqlitePlatform;

class SQLiteForeignKeyEnabler
{
    public function postConnect(ConnectionEventArgs $args): void
    {
        $connection = $args->getConnection();
        if ($connection->getDatabasePlatform() instanceof SqlitePlatform) {
            $connection->executeStatement('PRAGMA foreign_keys = ON;');
        }
    }
}
