<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909022209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

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
        $this->addSql('DROP TABLE budget_excluded_accounts');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE budget_excluded_accounts (budget_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , account_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , PRIMARY KEY(budget_id, account_id))');
        $this->addSql('CREATE INDEX IDX_75117D4836ABA6B8 ON budget_excluded_accounts (budget_id)');
        $this->addSql('CREATE INDEX IDX_75117D489B6B5FBA ON budget_excluded_accounts (account_id)');
        $this->addSql('DROP TABLE budgets_excluded_accounts');
        $this->addSql('DROP TABLE budgets_access');
    }
}
