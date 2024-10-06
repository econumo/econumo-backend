<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915221226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_entities_options AS SELECT entity_id, budget_id, currency_id, created_at, updated_at, position, entity_type FROM budgets_entities_options');
        $this->addSql('DROP TABLE budgets_entities_options');
        $this->addSql('CREATE TABLE budgets_entities_options (entity_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , currency_id CHAR(36) DEFAULT NULL COLLATE BINARY --(DC2Type:uuid)
        , folder_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, position SMALLINT UNSIGNED DEFAULT null, entity_type SMALLINT NOT NULL, PRIMARY KEY(entity_id), CONSTRAINT FK_B4F51BF836ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4F51BF838248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4F51BF8162CB942 FOREIGN KEY (folder_id) REFERENCES budgets_folders (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budgets_entities_options (entity_id, budget_id, currency_id, created_at, updated_at, position, entity_type) SELECT entity_id, budget_id, currency_id, created_at, updated_at, position, entity_type FROM __temp__budgets_entities_options');
        $this->addSql('DROP TABLE __temp__budgets_entities_options');
        $this->addSql('CREATE INDEX IDX_B4F51BF836ABA6B8 ON budgets_entities_options (budget_id)');
        $this->addSql('CREATE INDEX IDX_B4F51BF838248176 ON budgets_entities_options (currency_id)');
        $this->addSql('CREATE INDEX IDX_B4F51BF8162CB942 ON budgets_entities_options (folder_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_B4F51BF836ABA6B8');
        $this->addSql('DROP INDEX IDX_B4F51BF838248176');
        $this->addSql('DROP INDEX IDX_B4F51BF8162CB942');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budgets_entities_options AS SELECT entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position FROM budgets_entities_options');
        $this->addSql('DROP TABLE budgets_entities_options');
        $this->addSql('CREATE TABLE budgets_entities_options (entity_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, entity_type SMALLINT NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, finished_at DATETIME DEFAULT NULL, PRIMARY KEY(entity_id))');
        $this->addSql('INSERT INTO budgets_entities_options (entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position) SELECT entity_id, budget_id, currency_id, entity_type, created_at, updated_at, position FROM __temp__budgets_entities_options');
        $this->addSql('DROP TABLE __temp__budgets_entities_options');
        $this->addSql('CREATE INDEX IDX_B4F51BF836ABA6B8 ON budgets_entities_options (budget_id)');
        $this->addSql('CREATE INDEX IDX_B4F51BF838248176 ON budgets_entities_options (currency_id)');
    }
}
