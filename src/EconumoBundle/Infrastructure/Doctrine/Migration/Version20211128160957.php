<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128160957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE account_access_invites ADD CONSTRAINT FK_99AC74739B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_access_invites ADD CONSTRAINT FK_99AC7473E92F8F78 FOREIGN KEY (recipient_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_access_invites ADD CONSTRAINT FK_99AC74737E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_99AC74739B6B5FBA ON account_access_invites (account_id)');
        $this->addSql('CREATE INDEX IDX_99AC7473E92F8F78 ON account_access_invites (recipient_id)');
        $this->addSql('CREATE INDEX IDX_99AC74737E3C61F9 ON account_access_invites (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE account_access_invites DROP CONSTRAINT FK_99AC74739B6B5FBA');
        $this->addSql('ALTER TABLE account_access_invites DROP CONSTRAINT FK_99AC7473E92F8F78');
        $this->addSql('ALTER TABLE account_access_invites DROP CONSTRAINT FK_99AC74737E3C61F9');
        $this->addSql('DROP INDEX IDX_99AC74739B6B5FBA');
        $this->addSql('DROP INDEX IDX_99AC7473E92F8F78');
        $this->addSql('DROP INDEX IDX_99AC74737E3C61F9');
    }
}
