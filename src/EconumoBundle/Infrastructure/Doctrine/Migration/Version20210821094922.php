<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210821094922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('CREATE TABLE "transactions" (id UUID NOT NULL, user_id UUID NOT NULL, type SMALLINT NOT NULL, account_id UUID NOT NULL, account_recipient_id UUID DEFAULT NULL, amount NUMERIC(19, 2) NOT NULL, amount_recipient NUMERIC(19, 2) DEFAULT NULL, category_id UUID NOT NULL, description VARCHAR(255) NOT NULL, payee_id UUID DEFAULT NULL, tag_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, spent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "transactions".created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('DROP TABLE "transactions"');
    }
}
