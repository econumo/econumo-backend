<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904000307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE budgets (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(64) NOT NULL, start_date DATETIME NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCAA9548A76ED395 ON budgets (user_id)');
        $this->addSql('CREATE TABLE budget_excluded_accounts (budget_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(budget_id, account_id))');
        $this->addSql('CREATE INDEX IDX_75117D4836ABA6B8 ON budget_excluded_accounts (budget_id)');
        $this->addSql('CREATE INDEX IDX_75117D489B6B5FBA ON budget_excluded_accounts (account_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE budgets');
        $this->addSql('DROP TABLE budget_excluded_accounts');
    }
}
