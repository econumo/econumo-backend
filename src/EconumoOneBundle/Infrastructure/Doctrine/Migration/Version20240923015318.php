<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923015318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX budget_period_idx');
        $this->addSql('DROP INDEX IDX_E792AFB836ABA6B8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_entities_amounts AS SELECT budget_id, entity_id, entity_type, amount, period, created_at, updated_at, notes FROM budgets_entities_amounts');
        $this->addSql('DROP TABLE budgets_entities_amounts');
        $this->addSql('CREATE TABLE budgets_entities_amounts (budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , entity_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , entity_type SMALLINT NOT NULL, amount NUMERIC(19, 2) NOT NULL
        , period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, notes CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(entity_id, entity_type, period, budget_id), CONSTRAINT FK_E792AFB836ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets_entities_amounts (budget_id, entity_id, entity_type, amount, period, created_at, updated_at, notes) SELECT budget_id, entity_id, entity_type, amount, period, created_at, updated_at, notes FROM __temp__budgets_entities_amounts');
        $this->addSql('DROP TABLE __temp__budgets_entities_amounts');
        $this->addSql('CREATE INDEX budget_period_idx ON budgets_entities_amounts (budget_id, period)');
        $this->addSql('CREATE INDEX IDX_E792AFB836ABA6B8 ON budgets_entities_amounts (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_E792AFB836ABA6B8');
        $this->addSql('DROP INDEX budget_period_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_entities_amounts AS SELECT entity_id, entity_type, period, budget_id, amount, notes, created_at, updated_at FROM budgets_entities_amounts');
        $this->addSql('DROP TABLE budgets_entities_amounts');
        $this->addSql('CREATE TABLE budgets_entities_amounts (entity_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , entity_type SMALLINT NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , amount NUMERIC(19, 2) NOT NULL, notes CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(budget_id, entity_id, entity_type))');
        $this->addSql('INSERT INTO budgets_entities_amounts (entity_id, entity_type, period, budget_id, amount, notes, created_at, updated_at) SELECT entity_id, entity_type, period, budget_id, amount, notes, created_at, updated_at FROM __temp__budgets_entities_amounts');
        $this->addSql('DROP TABLE __temp__budgets_entities_amounts');
        $this->addSql('CREATE INDEX IDX_E792AFB836ABA6B8 ON budgets_entities_amounts (budget_id)');
        $this->addSql('CREATE INDEX budget_period_idx ON budgets_entities_amounts (budget_id, period)');
    }
}
