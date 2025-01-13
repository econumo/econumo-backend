<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113044324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', "Migration can only be executed safely on 'sqlite'.");

        $this->addSql("ALTER TABLE currencies ADD COLUMN fraction_digits SMALLINT DEFAULT '8' NOT NULL");
    }

    public function down(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', "Migration can only be executed safely on 'sqlite'.");

        $this->addSql('ALTER TABLE currencies DROP COLUMN fraction_digits');
    }
}
