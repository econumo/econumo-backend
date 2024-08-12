<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230917045851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE plan_access (plan_id UUID NOT NULL, user_id UUID NOT NULL, role SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(plan_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B2313326E899029B ON plan_access (plan_id)');
        $this->addSql('CREATE INDEX IDX_B2313326A76ED395 ON plan_access (user_id)');
        $this->addSql('COMMENT ON COLUMN plan_access.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE plan_options (plan_id UUID NOT NULL, user_id UUID NOT NULL, position SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(plan_id, user_id))');
        $this->addSql('CREATE INDEX IDX_6E8AB28FE899029B ON plan_options (plan_id)');
        $this->addSql('CREATE INDEX IDX_6E8AB28FA76ED395 ON plan_options (user_id)');
        $this->addSql('COMMENT ON COLUMN plan_options.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE plans (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(64) NOT NULL, is_archived BOOLEAN DEFAULT \'false\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_356798D1A76ED395 ON plans (user_id)');
        $this->addSql('COMMENT ON COLUMN plans.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE plan_access ADD CONSTRAINT FK_B2313326E899029B FOREIGN KEY (plan_id) REFERENCES plans (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_access ADD CONSTRAINT FK_B2313326A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_options ADD CONSTRAINT FK_6E8AB28FE899029B FOREIGN KEY (plan_id) REFERENCES plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_options ADD CONSTRAINT FK_6E8AB28FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plans ADD CONSTRAINT FK_356798D1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plan_access DROP CONSTRAINT FK_B2313326E899029B');
        $this->addSql('ALTER TABLE plan_options DROP CONSTRAINT FK_6E8AB28FE899029B');
        $this->addSql('DROP TABLE plan_access');
        $this->addSql('DROP TABLE plan_options');
        $this->addSql('DROP TABLE plans');
    }
}
