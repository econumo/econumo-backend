<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210820192632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE categories ADD type SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE categories DROP level');
        $this->addSql('ALTER TABLE categories DROP is_income');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE "categories" ADD level SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE "categories" ADD is_income BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE "categories" DROP type');
    }
}
