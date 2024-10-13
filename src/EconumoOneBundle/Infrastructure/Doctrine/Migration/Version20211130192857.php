<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211130192857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE TABLE folder_accounts (folder_id UUID NOT NULL, account_id UUID NOT NULL, PRIMARY KEY(folder_id, account_id))');
        $this->addSql('CREATE INDEX IDX_37D3D46162CB942 ON folder_accounts (folder_id)');
        $this->addSql('CREATE INDEX IDX_37D3D469B6B5FBA ON folder_accounts (account_id)');
        $this->addSql('ALTER TABLE folder_accounts ADD CONSTRAINT FK_37D3D46162CB942 FOREIGN KEY (folder_id) REFERENCES folders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE folder_accounts ADD CONSTRAINT FK_37D3D469B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE folders ADD CONSTRAINT FK_FE37D30FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FE37D30FA76ED395 ON folders (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('DROP TABLE folder_accounts');
        $this->addSql('ALTER TABLE folders DROP CONSTRAINT FK_FE37D30FA76ED395');
        $this->addSql('DROP INDEX IDX_FE37D30FA76ED395');
    }
}
