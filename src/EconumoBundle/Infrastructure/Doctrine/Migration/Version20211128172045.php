<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128172045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C70F7993E FOREIGN KEY (account_recipient_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CCB4B68F FOREIGN KEY (payee_id) REFERENCES payees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EAA81A4CA76ED395 ON transactions (user_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C9B6B5FBA ON transactions (account_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C70F7993E ON transactions (account_recipient_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C12469DE2 ON transactions (category_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CCB4B68F ON transactions (payee_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CBAD26311 ON transactions (tag_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', "Migration can only be executed safely on 'postgresql'.");

        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4CA76ED395');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C9B6B5FBA');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C70F7993E');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C12469DE2');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4CCB4B68F');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4CBAD26311');
        $this->addSql('DROP INDEX IDX_EAA81A4CA76ED395');
        $this->addSql('DROP INDEX IDX_EAA81A4C9B6B5FBA');
        $this->addSql('DROP INDEX IDX_EAA81A4C70F7993E');
        $this->addSql('DROP INDEX IDX_EAA81A4C12469DE2');
        $this->addSql('DROP INDEX IDX_EAA81A4CCB4B68F');
        $this->addSql('DROP INDEX IDX_EAA81A4CBAD26311');
    }
}
