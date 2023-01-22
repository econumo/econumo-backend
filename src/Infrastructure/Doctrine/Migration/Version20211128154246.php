<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128154246 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE account_access ADD CONSTRAINT FK_215DE5279B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_access ADD CONSTRAINT FK_215DE527A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_215DE5279B6B5FBA ON account_access (account_id)');
        $this->addSql('CREATE INDEX IDX_215DE527A76ED395 ON account_access (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE account_access DROP CONSTRAINT FK_215DE5279B6B5FBA');
        $this->addSql('ALTER TABLE account_access DROP CONSTRAINT FK_215DE527A76ED395');
        $this->addSql('DROP INDEX IDX_215DE5279B6B5FBA');
        $this->addSql('DROP INDEX IDX_215DE527A76ED395');
    }
}
