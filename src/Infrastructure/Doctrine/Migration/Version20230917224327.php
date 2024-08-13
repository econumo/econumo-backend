<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230917224327 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO user_options (id, user_id, name, value, created_at, updated_at) SELECT gen_random_uuid(), u.id, 'currency', 'USD', NOW(), NOW() FROM users u ON CONFLICT DO NOTHING;");
        $this->addSql("INSERT INTO user_options (id, user_id, name, value, created_at, updated_at) SELECT gen_random_uuid(), u.id, 'report_period', 'monthly', NOW(), NOW() FROM users u ON CONFLICT DO NOTHING;");
        $this->addSql("INSERT INTO user_options (id, user_id, name, value, created_at, updated_at) SELECT gen_random_uuid(), u.id, 'default_plan', null, NOW(), NOW() FROM users u ON CONFLICT DO NOTHING;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
