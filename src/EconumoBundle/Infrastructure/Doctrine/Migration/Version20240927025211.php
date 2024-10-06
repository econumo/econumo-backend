<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240927025211 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_DCAA9548A76ED395');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('CREATE TABLE budgets (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, started_at DATETIME NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_DCAA954838248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DCAA9548A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DCAA9548A76ED395 ON budgets (user_id)');
        $this->addSql('CREATE INDEX IDX_DCAA954838248176 ON budgets (currency_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_DCAA954838248176');
        $this->addSql('DROP INDEX IDX_DCAA9548A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets AS SELECT id, user_id, name, started_at, created_at, updated_at FROM budgets');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('CREATE TABLE budgets (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , started_at DATETIME NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO budgets (id, user_id, name, started_at, created_at, updated_at) SELECT id, user_id, name, started_at, created_at, updated_at FROM __temp__budgets');
        $this->addSql('DROP TABLE __temp__budgets');
        $this->addSql('CREATE INDEX IDX_DCAA9548A76ED395 ON budgets (user_id)');
    }
}
