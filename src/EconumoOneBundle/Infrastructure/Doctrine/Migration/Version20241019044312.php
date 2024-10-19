<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019044312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE budgets (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, started_at DATETIME NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCAA954838248176 ON budgets (currency_id)');
        $this->addSql('CREATE INDEX IDX_DCAA9548A76ED395 ON budgets (user_id)');
        $this->addSql('CREATE TABLE budgets_excluded_accounts (budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(budget_id, account_id))');
        $this->addSql('CREATE INDEX IDX_622BD95836ABA6B8 ON budgets_excluded_accounts (budget_id)');
        $this->addSql('CREATE INDEX IDX_622BD9589B6B5FBA ON budgets_excluded_accounts (account_id)');
        $this->addSql('CREATE TABLE budgets_access (budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , role SMALLINT NOT NULL, is_accepted BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(budget_id, user_id))');
        $this->addSql('CREATE INDEX IDX_9300F12F36ABA6B8 ON budgets_access (budget_id)');
        $this->addSql('CREATE INDEX IDX_9300F12FA76ED395 ON budgets_access (user_id)');
        $this->addSql('CREATE TABLE budgets_elements_amounts (element_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , element_type SMALLINT NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , amount NUMERIC(19, 2) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(element_id, element_type, period, budget_id))');
        $this->addSql('CREATE INDEX IDX_2FAF76336ABA6B8 ON budgets_elements_amounts (budget_id)');
        $this->addSql('CREATE INDEX budget_period_idx ON budgets_elements_amounts (budget_id, period)');
        $this->addSql('CREATE TABLE budgets_elements_options (element_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , element_type SMALLINT NOT NULL, budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , folder_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, PRIMARY KEY(element_id, element_type, budget_id))');
        $this->addSql('CREATE INDEX IDX_519D432336ABA6B8 ON budgets_elements_options (budget_id)');
        $this->addSql('CREATE INDEX IDX_519D432338248176 ON budgets_elements_options (currency_id)');
        $this->addSql('CREATE INDEX IDX_519D4323162CB942 ON budgets_elements_options (folder_id)');
        $this->addSql('CREATE TABLE budgets_envelopes (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) DEFAULT NULL, icon VARCHAR(64) DEFAULT NULL, is_archived BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FF0C05436ABA6B8 ON budgets_envelopes (budget_id)');
        $this->addSql('CREATE TABLE budgets_envelopes_categories (budget_envelope_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , category_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(budget_envelope_id, category_id))');
        $this->addSql('CREATE INDEX IDX_8F2B05CC310C8D48 ON budgets_envelopes_categories (budget_envelope_id)');
        $this->addSql('CREATE INDEX IDX_8F2B05CC12469DE2 ON budgets_envelopes_categories (category_id)');
        $this->addSql('CREATE TABLE budgets_folders (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3975126136ABA6B8 ON budgets_folders (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE budgets');
        $this->addSql('DROP TABLE budgets_excluded_accounts');
        $this->addSql('DROP TABLE budgets_access');
        $this->addSql('DROP TABLE budgets_elements_amounts');
        $this->addSql('DROP TABLE budgets_elements_options');
        $this->addSql('DROP TABLE budgets_envelopes');
        $this->addSql('DROP TABLE budgets_envelopes_categories');
        $this->addSql('DROP TABLE budgets_folders');
    }
}
