<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923001558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE INDEX type_idx_transactions ON transactions (type)');
        $this->addSql('CREATE INDEX spent_idx_transactions ON transactions (spent_at)');
        $this->addSql('CREATE INDEX account_spent_idx_transactions ON transactions (account_id, spent_at)');
        $this->addSql('CREATE INDEX account_recipient_spent_idx_transactions ON transactions (account_recipient_id, spent_at)');
        $this->addSql('CREATE INDEX category_account_spent_idx_transactions ON transactions (category_id, account_id, spent_at)');
        $this->addSql('CREATE INDEX tag_account_spent_idx_transactions ON transactions (tag_id, account_id, spent_at)');

        $this->addSql('CREATE INDEX published_at_idx_currency_rates ON currency_rates (published_at)');
        $this->addSql('CREATE INDEX currency_published_idx_currency_rates ON currency_rates (currency_id, published_at)');
        $this->addSql('CREATE INDEX base_currency_published_idx_currency_rates ON currency_rates (base_currency_id, published_at)');

        $this->addSql('CREATE INDEX user_deleted_idx_accounts ON accounts (user_id, is_deleted)');
        $this->addSql('CREATE INDEX is_deleted_idx_accounts ON accounts (is_deleted)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX type_idx_transactions');
        $this->addSql('DROP INDEX spent_idx_transactions');
        $this->addSql('DROP INDEX account_spent_idx_transactions');
        $this->addSql('DROP INDEX account_recipient_spent_idx_transactions');
        $this->addSql('DROP INDEX category_account_spent_idx_transactions');
        $this->addSql('DROP INDEX tag_account_spent_idx_transactions');

        $this->addSql('DROP INDEX published_at_idx_currency_rates');
        $this->addSql('DROP INDEX currency_published_idx_currency_rates');
        $this->addSql('DROP INDEX base_currency_published_idx_currency_rates');

        $this->addSql('DROP INDEX user_deleted_idx_accounts');
        $this->addSql('DROP INDEX is_deleted_idx_accounts');
    }
}
