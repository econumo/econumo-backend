<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116100634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE TABLE user_connections_invites (user_id UUID NOT NULL, code VARCHAR(255) DEFAULT NULL, expired_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE INDEX expired_at_idx ON user_connections_invites (expired_at)');
        $this->addSql('CREATE INDEX user_id_idx ON user_connections_invites (user_id)');
        $this->addSql('CREATE UNIQUE INDEX code_idx ON user_connections_invites (code)');
        $this->addSql('CREATE TABLE user_connections (user_id UUID NOT NULL, connected_user_id UUID NOT NULL, PRIMARY KEY(user_id, connected_user_id))');
        $this->addSql('CREATE INDEX IDX_16ED3580A76ED395 ON user_connections (user_id)');
        $this->addSql('CREATE INDEX IDX_16ED3580349E946C ON user_connections (connected_user_id)');
        $this->addSql('ALTER TABLE user_connections_invites ADD CONSTRAINT FK_9B2CEE40A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_connections ADD CONSTRAINT FK_16ED3580A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_connections ADD CONSTRAINT FK_16ED3580349E946C FOREIGN KEY (connected_user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('INSERT INTO user_connections_invites (user_id, code, expired_at) SELECT u.id, NULL, NULL FROM users u ON CONFLICT DO NOTHING');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('DROP TABLE user_connections_invites');
        $this->addSql('DROP TABLE user_connections');
    }
}
