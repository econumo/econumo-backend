<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217195918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('DROP INDEX uniq_37c44693e16c6b94');
        $this->addSql('ALTER TABLE currencies RENAME COLUMN sign TO symbol;');
        $this->addSql('ALTER TABLE currencies RENAME COLUMN alias TO code;');
        $this->addSql('ALTER TABLE currencies ALTER symbol TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE currencies ALTER code TYPE CHAR(3)');
        $this->addSql('ALTER TABLE currencies DROP updated_at');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_37C4469377153098 ON currencies (code)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

    }
}
