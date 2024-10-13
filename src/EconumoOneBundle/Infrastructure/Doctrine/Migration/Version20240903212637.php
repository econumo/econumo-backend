<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903212637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE envelope_budgets');
        $this->addSql('DROP TABLE envelope_categories');
        $this->addSql('DROP TABLE envelope_tags');
        $this->addSql('DROP TABLE envelopes');
        $this->addSql('DROP TABLE plan_access');
        $this->addSql('DROP TABLE plan_exchange_budget');
        $this->addSql('DROP TABLE plan_folders');
        $this->addSql('DROP TABLE plan_options');
        $this->addSql('DROP TABLE plans');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE envelope_budgets (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , envelope_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , amount NUMERIC(19, 2) NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2967EB44706CB17 ON envelope_budgets (envelope_id)');
        $this->addSql('CREATE TABLE envelope_categories (envelope_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , category_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , PRIMARY KEY(envelope_id, category_id))');
        $this->addSql('CREATE INDEX IDX_14C89A6212469DE2 ON envelope_categories (category_id)');
        $this->addSql('CREATE INDEX IDX_14C89A624706CB17 ON envelope_categories (envelope_id)');
        $this->addSql('CREATE TABLE envelope_tags (envelope_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , tag_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , PRIMARY KEY(envelope_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_FE45A18BAD26311 ON envelope_tags (tag_id)');
        $this->addSql('CREATE INDEX IDX_FE45A184706CB17 ON envelope_tags (envelope_id)');
        $this->addSql('CREATE TABLE envelopes (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , plan_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , folder_id CHAR(36) DEFAULT NULL COLLATE BINARY --(DC2Type:uuid)
        , type SMALLINT NOT NULL, position INTEGER NOT NULL, name VARCHAR(64) DEFAULT NULL COLLATE BINARY, icon VARCHAR(64) DEFAULT NULL COLLATE BINARY, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_58EDB319162CB942 ON envelopes (folder_id)');
        $this->addSql('CREATE INDEX IDX_58EDB31938248176 ON envelopes (currency_id)');
        $this->addSql('CREATE INDEX IDX_58EDB319E899029B ON envelopes (plan_id)');
        $this->addSql('CREATE TABLE plan_access (plan_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , role SMALLINT NOT NULL, is_accepted BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(plan_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B2313326A76ED395 ON plan_access (user_id)');
        $this->addSql('CREATE INDEX IDX_B2313326E899029B ON plan_access (plan_id)');
        $this->addSql('CREATE TABLE plan_exchange_budget (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , plan_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , amount NUMERIC(19, 2) NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB813EEB38248176 ON plan_exchange_budget (currency_id)');
        $this->addSql('CREATE INDEX IDX_EB813EEBE899029B ON plan_exchange_budget (plan_id)');
        $this->addSql('CREATE TABLE plan_folders (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , plan_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL COLLATE BINARY, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40889B07E899029B ON plan_folders (plan_id)');
        $this->addSql('CREATE TABLE plan_options (plan_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(plan_id, user_id))');
        $this->addSql('CREATE INDEX IDX_6E8AB28FA76ED395 ON plan_options (user_id)');
        $this->addSql('CREATE INDEX IDX_6E8AB28FE899029B ON plan_options (plan_id)');
        $this->addSql('CREATE TABLE plans (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL COLLATE BINARY, start_date DATETIME NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_356798D1A76ED395 ON plans (user_id)');
    }
}
