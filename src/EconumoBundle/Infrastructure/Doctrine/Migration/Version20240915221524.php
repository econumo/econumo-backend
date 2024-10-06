<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915221524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_4FF0C05436ABA6B8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_envelopes AS SELECT id, budget_id, created_at, updated_at, name, icon FROM budgets_envelopes');
        $this->addSql('DROP TABLE budgets_envelopes');
        $this->addSql('CREATE TABLE budgets_envelopes (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) DEFAULT NULL, icon VARCHAR(64) DEFAULT NULL, is_archived BOOLEAN DEFAULT \'0\' NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_4FF0C05436ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets_envelopes (id, budget_id, created_at, updated_at, name, icon) SELECT id, budget_id, created_at, updated_at, name, icon FROM __temp__budgets_envelopes');
        $this->addSql('DROP TABLE __temp__budgets_envelopes');
        $this->addSql('CREATE INDEX IDX_4FF0C05436ABA6B8 ON budgets_envelopes (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_4FF0C05436ABA6B8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_envelopes AS SELECT id, budget_id, name, icon, created_at, updated_at FROM budgets_envelopes');
        $this->addSql('DROP TABLE budgets_envelopes');
        $this->addSql('CREATE TABLE budgets_envelopes (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) DEFAULT NULL COLLATE BINARY, icon VARCHAR(64) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO budgets_envelopes (id, budget_id, name, icon, created_at, updated_at) SELECT id, budget_id, name, icon, created_at, updated_at FROM __temp__budgets_envelopes');
        $this->addSql('DROP TABLE __temp__budgets_envelopes');
        $this->addSql('CREATE INDEX IDX_4FF0C05436ABA6B8 ON budgets_envelopes (budget_id)');
    }
}
