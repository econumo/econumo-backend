<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230917202434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');


        $this->addSql('ALTER TABLE plan_access DROP CONSTRAINT FK_B2313326E899029B');
        $this->addSql('ALTER TABLE plan_access DROP CONSTRAINT FK_B2313326A76ED395');
        $this->addSql('ALTER TABLE plan_access ADD CONSTRAINT FK_B2313326E899029B FOREIGN KEY (plan_id) REFERENCES plans (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_access ADD CONSTRAINT FK_B2313326A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE plan_access DROP CONSTRAINT fk_b2313326e899029b');
        $this->addSql('ALTER TABLE plan_access DROP CONSTRAINT fk_b2313326a76ed395');
        $this->addSql('ALTER TABLE plan_access ADD CONSTRAINT fk_b2313326e899029b FOREIGN KEY (plan_id) REFERENCES plans (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plan_access ADD CONSTRAINT fk_b2313326a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
