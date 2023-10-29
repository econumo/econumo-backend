<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020013838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE plan_exchange_budget (id UUID NOT NULL, plan_id UUID NOT NULL, currency_id UUID NOT NULL, amount NUMERIC(19, 2) NOT NULL, period TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB813EEBE899029B ON plan_exchange_budget (plan_id)');
        $this->addSql('CREATE INDEX IDX_EB813EEB38248176 ON plan_exchange_budget (currency_id)');
        $this->addSql('COMMENT ON COLUMN plan_exchange_budget.period IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN plan_exchange_budget.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE plan_exchange_budget ADD CONSTRAINT FK_EB813EEBE899029B FOREIGN KEY (plan_id) REFERENCES plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_exchange_budget ADD CONSTRAINT FK_EB813EEB38248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE plan_exchange_budget');
    }
}
