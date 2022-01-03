<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103084323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C70F7993E');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C12469DE2');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4CCB4B68F');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4CBAD26311');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C70F7993E FOREIGN KEY (account_recipient_id) REFERENCES accounts (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CCB4B68F FOREIGN KEY (payee_id) REFERENCES payees (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT fk_eaa81a4c70f7993e');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT fk_eaa81a4c12469de2');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT fk_eaa81a4ccb4b68f');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT fk_eaa81a4cbad26311');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT fk_eaa81a4c70f7993e FOREIGN KEY (account_recipient_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT fk_eaa81a4c12469de2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT fk_eaa81a4ccb4b68f FOREIGN KEY (payee_id) REFERENCES payees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT fk_eaa81a4cbad26311 FOREIGN KEY (tag_id) REFERENCES tags (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
