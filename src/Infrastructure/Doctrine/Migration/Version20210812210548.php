<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812210548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE TABLE "accounts" (id UUID NOT NULL, name VARCHAR(64) NOT NULL, position SMALLINT DEFAULT 0 NOT NULL, currency_id UUID NOT NULL, balance NUMERIC(19, 2) NOT NULL, type SMALLINT NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "accounts".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "categories" (id UUID NOT NULL, name VARCHAR(64) NOT NULL, level SMALLINT DEFAULT 0 NOT NULL, position SMALLINT DEFAULT 0 NOT NULL, is_income BOOLEAN DEFAULT \'false\' NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "categories".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "currencies" (id UUID NOT NULL, sign VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "currencies".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "users" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, identifier VARCHAR(40) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(40) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9772E836A ON "users" (identifier)');
        $this->addSql('COMMENT ON COLUMN "users".created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "accounts"');
        $this->addSql('DROP TABLE "categories"');
        $this->addSql('DROP TABLE "currencies"');
        $this->addSql('DROP TABLE "users"');
    }
}
