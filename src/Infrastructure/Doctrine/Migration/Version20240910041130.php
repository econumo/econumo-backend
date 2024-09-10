<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910041130 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE budgets_entities_amounts (entity_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , entity_type SMALLINT NOT NULL, amount NUMERIC(19, 2) NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(entity_id))');
        $this->addSql('CREATE INDEX IDX_E792AFB836ABA6B8 ON budgets_entities_amounts (budget_id)');
        $this->addSql('CREATE INDEX budget_period_idx ON budgets_entities_amounts (budget_id, period)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets AS SELECT id, user_id, name, start_date, created_at, updated_at FROM budgets');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('CREATE TABLE budgets (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, started_at DATETIME NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_DCAA9548A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets (id, user_id, name, started_at, created_at, updated_at) SELECT id, user_id, name, start_date, created_at, updated_at FROM __temp__budgets');
        $this->addSql('DROP TABLE __temp__budgets');
        $this->addSql('CREATE INDEX IDX_DCAA9548A76ED395 ON budgets (user_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_entities_options AS SELECT entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position FROM budgets_entities_options');
        $this->addSql('DROP TABLE budgets_entities_options');
        $this->addSql('CREATE TABLE budgets_entities_options (entity_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , currency_id CHAR(36) DEFAULT NULL COLLATE BINARY --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, entity_type SMALLINT NOT NULL, finished_at DATETIME DEFAULT NULL, PRIMARY KEY(entity_id), CONSTRAINT FK_B4F51BF836ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4F51BF838248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets_entities_options (entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position) SELECT entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position FROM __temp__budgets_entities_options');
        $this->addSql('DROP TABLE __temp__budgets_entities_options');
        $this->addSql('CREATE INDEX IDX_B4F51BF838248176 ON budgets_entities_options (currency_id)');
        $this->addSql('CREATE INDEX IDX_B4F51BF836ABA6B8 ON budgets_entities_options (budget_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_envelopes AS SELECT id, budget_id, name, icon, created_at, updated_at FROM budgets_envelopes');
        $this->addSql('DROP TABLE budgets_envelopes');
        $this->addSql('CREATE TABLE budgets_envelopes (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) DEFAULT NULL, icon VARCHAR(64) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_4FF0C05436ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets_envelopes (id, budget_id, name, icon, created_at, updated_at) SELECT id, budget_id, name, icon, created_at, updated_at FROM __temp__budgets_envelopes');
        $this->addSql('DROP TABLE __temp__budgets_envelopes');
        $this->addSql('CREATE INDEX IDX_4FF0C05436ABA6B8 ON budgets_envelopes (budget_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_folders AS SELECT id, budget_id, name, position, created_at, updated_at FROM budgets_folders');
        $this->addSql('DROP TABLE budgets_folders');
        $this->addSql('CREATE TABLE budgets_folders (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_3975126136ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets_folders (id, budget_id, name, position, created_at, updated_at) SELECT id, budget_id, name, position, created_at, updated_at FROM __temp__budgets_folders');
        $this->addSql('DROP TABLE __temp__budgets_folders');
        $this->addSql('CREATE INDEX IDX_3975126136ABA6B8 ON budgets_folders (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE budgets_entities_amounts');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets AS SELECT id, user_id, name, started_at, created_at, updated_at FROM budgets');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('CREATE TABLE budgets (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, start_date DATETIME NOT NULL, name VARCHAR(64) NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO budgets (id, user_id, name, start_date, created_at, updated_at) SELECT id, user_id, name, started_at, created_at, updated_at FROM __temp__budgets');
        $this->addSql('DROP TABLE __temp__budgets');
        $this->addSql('CREATE INDEX IDX_DCAA9548A76ED395 ON budgets (user_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_entities_options AS SELECT entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position FROM budgets_entities_options');
        $this->addSql('DROP TABLE budgets_entities_options');
        $this->addSql('CREATE TABLE budgets_entities_options (entity_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, entity_type SMALLINT NOT NULL, PRIMARY KEY(entity_id))');
        $this->addSql('INSERT INTO budgets_entities_options (entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position) SELECT entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position FROM __temp__budgets_entities_options');
        $this->addSql('DROP TABLE __temp__budgets_entities_options');
        $this->addSql('CREATE INDEX IDX_B4F51BF836ABA6B8 ON budgets_entities_options (budget_id)');
        $this->addSql('CREATE INDEX IDX_B4F51BF838248176 ON budgets_entities_options (currency_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_envelopes AS SELECT id, budget_id, name, icon, created_at, updated_at FROM budgets_envelopes');
        $this->addSql('DROP TABLE budgets_envelopes');
        $this->addSql('CREATE TABLE budgets_envelopes (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) DEFAULT NULL COLLATE BINARY, icon VARCHAR(64) DEFAULT NULL COLLATE BINARY, currency_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO budgets_envelopes (id, budget_id, name, icon, created_at, updated_at) SELECT id, budget_id, name, icon, created_at, updated_at FROM __temp__budgets_envelopes');
        $this->addSql('DROP TABLE __temp__budgets_envelopes');
        $this->addSql('CREATE INDEX IDX_4FF0C05436ABA6B8 ON budgets_envelopes (budget_id)');
        $this->addSql('CREATE INDEX IDX_4FF0C05438248176 ON budgets_envelopes (currency_id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_folders AS SELECT id, budget_id, name, position, created_at, updated_at FROM budgets_folders');
        $this->addSql('DROP TABLE budgets_folders');
        $this->addSql('CREATE TABLE budgets_folders (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, name VARCHAR(64) NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO budgets_folders (id, budget_id, name, position, created_at, updated_at) SELECT id, budget_id, name, position, created_at, updated_at FROM __temp__budgets_folders');
        $this->addSql('DROP TABLE __temp__budgets_folders');
        $this->addSql('CREATE INDEX IDX_3975126136ABA6B8 ON budgets_folders (budget_id)');
    }
}
