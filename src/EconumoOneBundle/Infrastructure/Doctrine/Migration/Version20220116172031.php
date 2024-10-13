<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116172031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('DROP TABLE account_access_invites');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE account_access_invites (account_id UUID NOT NULL, recipient_id UUID NOT NULL, owner_id UUID NOT NULL, role SMALLINT NOT NULL, code CHAR(5) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(account_id, recipient_id))');
        $this->addSql('CREATE INDEX idx_99ac74739b6b5fba ON account_access_invites (account_id)');
        $this->addSql('CREATE INDEX idx_99ac74737e3c61f9 ON account_access_invites (owner_id)');
        $this->addSql('CREATE INDEX idx_99ac7473e92f8f78 ON account_access_invites (recipient_id)');
        $this->addSql("COMMENT ON COLUMN account_access_invites.created_at IS '(DC2Type:datetime_immutable)'");
    }
}
