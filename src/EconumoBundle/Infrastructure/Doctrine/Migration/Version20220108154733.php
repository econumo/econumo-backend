<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220108154733 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE TABLE account_options (account_id UUID NOT NULL, user_id UUID NOT NULL, position SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(account_id, user_id))');
        $this->addSql('CREATE INDEX IDX_191EEECF9B6B5FBA ON account_options (account_id)');
        $this->addSql('CREATE INDEX IDX_191EEECFA76ED395 ON account_options (user_id)');
        $this->addSql("COMMENT ON COLUMN account_options.created_at IS '(DC2Type:datetime_immutable)'");
        $this->addSql('ALTER TABLE account_options ADD CONSTRAINT FK_191EEECF9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_options ADD CONSTRAINT FK_191EEECFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE accounts DROP "position"');

        $this->addSql('INSERT INTO account_options (account_id, user_id, position, created_at, updated_at) SELECT a.id, a.user_id, 0, a.created_at, a.updated_at FROM accounts a ON CONFLICT DO NOTHING');
        $this->addSql('INSERT INTO account_options (account_id, user_id, position, created_at, updated_at) SELECT aa.account_id, aa.user_id, 0, aa.created_at, aa.updated_at FROM account_access aa ON CONFLICT DO NOTHING');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('DROP TABLE account_options');
        $this->addSql('ALTER TABLE accounts ADD "position" SMALLINT DEFAULT 0 NOT NULL');
    }
}
