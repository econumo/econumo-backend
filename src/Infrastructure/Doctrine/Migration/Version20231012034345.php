<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012034345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE envelope_budgets (id UUID NOT NULL, envelope_id UUID NOT NULL, amount NUMERIC(19, 2) NOT NULL, period TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2967EB44706CB17 ON envelope_budgets (envelope_id)');
        $this->addSql('COMMENT ON COLUMN envelope_budgets.period IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN envelope_budgets.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE envelopes (id UUID NOT NULL, plan_id UUID NOT NULL, currency_id UUID NOT NULL, folder_id UUID DEFAULT NULL, type SMALLINT NOT NULL, position INT NOT NULL, name VARCHAR(64) DEFAULT NULL, icon VARCHAR(64) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_58EDB319E899029B ON envelopes (plan_id)');
        $this->addSql('CREATE INDEX IDX_58EDB31938248176 ON envelopes (currency_id)');
        $this->addSql('CREATE INDEX IDX_58EDB319162CB942 ON envelopes (folder_id)');
        $this->addSql('COMMENT ON COLUMN envelopes.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE envelope_categories (envelope_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY(envelope_id, category_id))');
        $this->addSql('CREATE INDEX IDX_14C89A624706CB17 ON envelope_categories (envelope_id)');
        $this->addSql('CREATE INDEX IDX_14C89A6212469DE2 ON envelope_categories (category_id)');
        $this->addSql('CREATE TABLE envelope_tags (envelope_id UUID NOT NULL, tag_id UUID NOT NULL, PRIMARY KEY(envelope_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_FE45A184706CB17 ON envelope_tags (envelope_id)');
        $this->addSql('CREATE INDEX IDX_FE45A18BAD26311 ON envelope_tags (tag_id)');
        $this->addSql('CREATE TABLE plan_folders (id UUID NOT NULL, plan_id UUID NOT NULL, name VARCHAR(64) NOT NULL, position SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40889B07E899029B ON plan_folders (plan_id)');
        $this->addSql('COMMENT ON COLUMN plan_folders.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE envelope_budgets ADD CONSTRAINT FK_C2967EB44706CB17 FOREIGN KEY (envelope_id) REFERENCES envelopes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT FK_58EDB319E899029B FOREIGN KEY (plan_id) REFERENCES plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT FK_58EDB31938248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT FK_58EDB319162CB942 FOREIGN KEY (folder_id) REFERENCES plan_folders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelope_categories ADD CONSTRAINT FK_14C89A624706CB17 FOREIGN KEY (envelope_id) REFERENCES envelopes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelope_categories ADD CONSTRAINT FK_14C89A6212469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelope_tags ADD CONSTRAINT FK_FE45A184706CB17 FOREIGN KEY (envelope_id) REFERENCES envelopes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelope_tags ADD CONSTRAINT FK_FE45A18BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_folders ADD CONSTRAINT FK_40889B07E899029B FOREIGN KEY (plan_id) REFERENCES plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE envelope_budgets DROP CONSTRAINT FK_C2967EB44706CB17');
        $this->addSql('ALTER TABLE envelope_categories DROP CONSTRAINT FK_14C89A624706CB17');
        $this->addSql('ALTER TABLE envelope_tags DROP CONSTRAINT FK_FE45A184706CB17');
        $this->addSql('ALTER TABLE envelopes DROP CONSTRAINT FK_58EDB319162CB942');
        $this->addSql('DROP TABLE envelope_budgets');
        $this->addSql('DROP TABLE envelopes');
        $this->addSql('DROP TABLE envelope_categories');
        $this->addSql('DROP TABLE envelope_tags');
        $this->addSql('DROP TABLE plan_folders');
    }
}
