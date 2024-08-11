<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811015229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE account_access (account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , role SMALLINT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(account_id, user_id))');
        $this->addSql('CREATE INDEX IDX_215DE5279B6B5FBA ON account_access (account_id)');
        $this->addSql('CREATE INDEX IDX_215DE527A76ED395 ON account_access (user_id)');
        $this->addSql('CREATE TABLE account_options (account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(account_id, user_id))');
        $this->addSql('CREATE INDEX IDX_191EEECF9B6B5FBA ON account_options (account_id)');
        $this->addSql('CREATE INDEX IDX_191EEECFA76ED395 ON account_options (user_id)');
        $this->addSql('CREATE TABLE accounts (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, balance NUMERIC(19, 2) NOT NULL, type SMALLINT NOT NULL, icon VARCHAR(64) NOT NULL, is_excluded_from_budget BOOLEAN DEFAULT \'0\' NOT NULL, is_deleted BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAC89EAC38248176 ON accounts (currency_id)');
        $this->addSql('CREATE INDEX IDX_CAC89EACA76ED395 ON accounts (user_id)');
        $this->addSql('CREATE TABLE categories (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, type SMALLINT NOT NULL, icon VARCHAR(255) NOT NULL, is_archived BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3AF34668A76ED395 ON categories (user_id)');
        $this->addSql('CREATE TABLE currencies (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , code CHAR(3) NOT NULL, symbol VARCHAR(12) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_37C4469377153098 ON currencies (code)');
        $this->addSql('CREATE TABLE currency_rates (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , base_currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , rate NUMERIC(16, 8) NOT NULL, published_at DATE NOT NULL --(DC2Type:date_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1336A95A38248176 ON currency_rates (currency_id)');
        $this->addSql('CREATE INDEX IDX_1336A95A3101778E ON currency_rates (base_currency_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_currency_rate ON currency_rates (published_at, currency_id, base_currency_id)');
        $this->addSql('CREATE TABLE envelope_budgets (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , envelope_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , amount NUMERIC(19, 2) NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2967EB44706CB17 ON envelope_budgets (envelope_id)');
        $this->addSql('CREATE TABLE envelopes (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , plan_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , folder_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , type SMALLINT NOT NULL, position INTEGER NOT NULL, name VARCHAR(64) DEFAULT NULL, icon VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_58EDB319E899029B ON envelopes (plan_id)');
        $this->addSql('CREATE INDEX IDX_58EDB31938248176 ON envelopes (currency_id)');
        $this->addSql('CREATE INDEX IDX_58EDB319162CB942 ON envelopes (folder_id)');
        $this->addSql('CREATE TABLE envelope_categories (envelope_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , category_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(envelope_id, category_id))');
        $this->addSql('CREATE INDEX IDX_14C89A624706CB17 ON envelope_categories (envelope_id)');
        $this->addSql('CREATE INDEX IDX_14C89A6212469DE2 ON envelope_categories (category_id)');
        $this->addSql('CREATE TABLE envelope_tags (envelope_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , tag_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(envelope_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_FE45A184706CB17 ON envelope_tags (envelope_id)');
        $this->addSql('CREATE INDEX IDX_FE45A18BAD26311 ON envelope_tags (tag_id)');
        $this->addSql('CREATE TABLE folders (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, is_visible BOOLEAN DEFAULT \'true\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE37D30FA76ED395 ON folders (user_id)');
        $this->addSql('CREATE TABLE folder_accounts (folder_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(folder_id, account_id))');
        $this->addSql('CREATE INDEX IDX_37D3D46162CB942 ON folder_accounts (folder_id)');
        $this->addSql('CREATE INDEX IDX_37D3D469B6B5FBA ON folder_accounts (account_id)');
        $this->addSql('CREATE TABLE "operation_requests_ids" (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , is_handled BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payees (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, is_archived BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_971FAB26A76ED395 ON payees (user_id)');
        $this->addSql('CREATE TABLE plan_access (plan_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , role SMALLINT NOT NULL, is_accepted BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(plan_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B2313326E899029B ON plan_access (plan_id)');
        $this->addSql('CREATE INDEX IDX_B2313326A76ED395 ON plan_access (user_id)');
        $this->addSql('CREATE TABLE plan_exchange_budget (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , plan_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , currency_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , amount NUMERIC(19, 2) NOT NULL, period DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB813EEBE899029B ON plan_exchange_budget (plan_id)');
        $this->addSql('CREATE INDEX IDX_EB813EEB38248176 ON plan_exchange_budget (currency_id)');
        $this->addSql('CREATE TABLE plan_folders (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , plan_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40889B07E899029B ON plan_folders (plan_id)');
        $this->addSql('CREATE TABLE plan_options (plan_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(plan_id, user_id))');
        $this->addSql('CREATE INDEX IDX_6E8AB28FE899029B ON plan_options (plan_id)');
        $this->addSql('CREATE INDEX IDX_6E8AB28FA76ED395 ON plan_options (user_id)');
        $this->addSql('CREATE TABLE plans (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, start_date DATETIME NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_356798D1A76ED395 ON plans (user_id)');
        $this->addSql('CREATE TABLE tags (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, position SMALLINT UNSIGNED DEFAULT 0 NOT NULL, is_archived BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FBC9426A76ED395 ON tags (user_id)');
        $this->addSql('CREATE TABLE transactions (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , account_recipient_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , category_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , payee_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , tag_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , type SMALLINT NOT NULL, amount NUMERIC(19, 2) NOT NULL, amount_recipient NUMERIC(19, 2) DEFAULT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, spent_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA81A4CA76ED395 ON transactions (user_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C9B6B5FBA ON transactions (account_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C70F7993E ON transactions (account_recipient_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C12469DE2 ON transactions (category_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CCB4B68F ON transactions (payee_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CBAD26311 ON transactions (tag_id)');
        $this->addSql('CREATE TABLE user_connections_invites (user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , code VARCHAR(255) DEFAULT NULL, expired_at DATETIME DEFAULT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE INDEX expired_at_idx ON user_connections_invites (expired_at)');
        $this->addSql('CREATE INDEX user_id_idx ON user_connections_invites (user_id)');
        $this->addSql('CREATE UNIQUE INDEX code_idx ON user_connections_invites (code)');
        $this->addSql('CREATE TABLE user_options (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, value VARCHAR(256) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8838E48DA76ED395 ON user_options (user_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_user_option_idx ON user_options (user_id, name)');
        $this->addSql('CREATE TABLE user_password_requests (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , code CHAR(12) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, expired_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_130DFFBAA76ED395 ON user_password_requests (user_id)');
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, identifier VARCHAR(256) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(40) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9772E836A ON users (identifier)');
        $this->addSql('CREATE TABLE user_connections (user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , connected_user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(user_id, connected_user_id))');
        $this->addSql('CREATE INDEX IDX_16ED3580A76ED395 ON user_connections (user_id)');
        $this->addSql('CREATE INDEX IDX_16ED3580349E946C ON user_connections (connected_user_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE account_access');
        $this->addSql('DROP TABLE account_options');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE currencies');
        $this->addSql('DROP TABLE currency_rates');
        $this->addSql('DROP TABLE envelope_budgets');
        $this->addSql('DROP TABLE envelopes');
        $this->addSql('DROP TABLE envelope_categories');
        $this->addSql('DROP TABLE envelope_tags');
        $this->addSql('DROP TABLE folders');
        $this->addSql('DROP TABLE folder_accounts');
        $this->addSql('DROP TABLE "operation_requests_ids"');
        $this->addSql('DROP TABLE payees');
        $this->addSql('DROP TABLE plan_access');
        $this->addSql('DROP TABLE plan_exchange_budget');
        $this->addSql('DROP TABLE plan_folders');
        $this->addSql('DROP TABLE plan_options');
        $this->addSql('DROP TABLE plans');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE user_connections_invites');
        $this->addSql('DROP TABLE user_options');
        $this->addSql('DROP TABLE user_password_requests');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_connections');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
