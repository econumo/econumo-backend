<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220121754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE currency_rates (id UUID NOT NULL, currency_id UUID NOT NULL, base_currency_id UUID NOT NULL, rate NUMERIC(12, 8) NOT NULL, published_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1336A95A38248176 ON currency_rates (currency_id)');
        $this->addSql('CREATE INDEX IDX_1336A95A3101778E ON currency_rates (base_currency_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_currency_rate ON currency_rates (published_at, currency_id, base_currency_id)');
        $this->addSql('COMMENT ON COLUMN currency_rates.published_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE currency_rates ADD CONSTRAINT FK_1336A95A38248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE currency_rates ADD CONSTRAINT FK_1336A95A3101778E FOREIGN KEY (base_currency_id) REFERENCES currencies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE currency_rates');
    }
}
