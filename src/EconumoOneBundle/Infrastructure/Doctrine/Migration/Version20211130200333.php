<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211130200333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE folder_accounts DROP CONSTRAINT FK_37D3D46162CB942');
        $this->addSql('ALTER TABLE folder_accounts DROP CONSTRAINT FK_37D3D469B6B5FBA');
        $this->addSql('ALTER TABLE folder_accounts ADD CONSTRAINT FK_37D3D46162CB942 FOREIGN KEY (folder_id) REFERENCES folders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE folder_accounts ADD CONSTRAINT FK_37D3D469B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE folder_accounts DROP CONSTRAINT fk_37d3d46162cb942');
        $this->addSql('ALTER TABLE folder_accounts DROP CONSTRAINT fk_37d3d469b6b5fba');
        $this->addSql('ALTER TABLE folder_accounts ADD CONSTRAINT fk_37d3d46162cb942 FOREIGN KEY (folder_id) REFERENCES folders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE folder_accounts ADD CONSTRAINT fk_37d3d469b6b5fba FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
