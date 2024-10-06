<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102163923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE categories ADD icon VARCHAR(255);');
        $this->addSql("UPDATE categories SET icon = 'local_offer'");
        $this->addSql('ALTER TABLE categories ALTER COLUMN icon SET NOT NULL;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE categories DROP icon');
    }
}
