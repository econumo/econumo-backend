<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019035622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE envelope_budgets DROP CONSTRAINT FK_C2967EB44706CB17');
        $this->addSql('ALTER TABLE envelope_budgets ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE envelope_budgets ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelope_budgets ALTER envelope_id TYPE UUID');
        $this->addSql('ALTER TABLE envelope_budgets ALTER envelope_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelope_budgets ADD CONSTRAINT FK_C2967EB44706CB17 FOREIGN KEY (envelope_id) REFERENCES envelopes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes DROP CONSTRAINT FK_58EDB31938248176');
        $this->addSql('ALTER TABLE envelopes DROP CONSTRAINT FK_58EDB319162CB942');
        $this->addSql('ALTER TABLE envelopes ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE envelopes ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ALTER plan_id TYPE UUID');
        $this->addSql('ALTER TABLE envelopes ALTER plan_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ALTER currency_id TYPE UUID');
        $this->addSql('ALTER TABLE envelopes ALTER currency_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ALTER folder_id TYPE UUID');
        $this->addSql('ALTER TABLE envelopes ALTER folder_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ALTER type TYPE SMALLINT');
        $this->addSql('ALTER TABLE envelopes ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ALTER name TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE envelopes ALTER name DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ALTER icon TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE envelopes ALTER icon DROP DEFAULT');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT FK_58EDB31938248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT FK_58EDB319162CB942 FOREIGN KEY (folder_id) REFERENCES plan_folders (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelope_categories ALTER envelope_id TYPE UUID');
        $this->addSql('ALTER TABLE envelope_categories ALTER envelope_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelope_categories ALTER category_id TYPE UUID');
        $this->addSql('ALTER TABLE envelope_categories ALTER category_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelope_tags ALTER envelope_id TYPE UUID');
        $this->addSql('ALTER TABLE envelope_tags ALTER envelope_id DROP DEFAULT');
        $this->addSql('ALTER TABLE envelope_tags ALTER tag_id TYPE UUID');
        $this->addSql('ALTER TABLE envelope_tags ALTER tag_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_access ALTER plan_id TYPE UUID');
        $this->addSql('ALTER TABLE plan_access ALTER plan_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_access ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE plan_access ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_access ALTER role TYPE SMALLINT');
        $this->addSql('ALTER TABLE plan_access ALTER role DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_folders ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE plan_folders ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_folders ALTER plan_id TYPE UUID');
        $this->addSql('ALTER TABLE plan_folders ALTER plan_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_folders ALTER name TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE plan_folders ALTER name DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_options ALTER plan_id TYPE UUID');
        $this->addSql('ALTER TABLE plan_options ALTER plan_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plan_options ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE plan_options ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plans ADD start_date TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE plans ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE plans ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE plans ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE plans ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE plans ALTER name TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE plans ALTER name DROP DEFAULT');
        $this->addSql('ALTER TABLE user_options DROP CONSTRAINT FK_8838E48DA76ED395');
        $this->addSql('ALTER TABLE user_options ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE user_options ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_options ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE user_options ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_options ADD CONSTRAINT FK_8838E48DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE envelope_budgets DROP CONSTRAINT fk_c2967eb44706cb17');
        $this->addSql('ALTER TABLE envelope_budgets ADD CONSTRAINT fk_c2967eb44706cb17 FOREIGN KEY (envelope_id) REFERENCES envelopes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes DROP CONSTRAINT fk_58edb31938248176');
        $this->addSql('ALTER TABLE envelopes DROP CONSTRAINT fk_58edb319162cb942');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT fk_58edb31938248176 FOREIGN KEY (currency_id) REFERENCES currencies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE envelopes ADD CONSTRAINT fk_58edb319162cb942 FOREIGN KEY (folder_id) REFERENCES plan_folders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plans DROP start_date');
        $this->addSql('ALTER TABLE user_options DROP CONSTRAINT fk_8838e48da76ed395');
        $this->addSql('ALTER TABLE user_options ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE user_options ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_options ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE user_options ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_options ADD CONSTRAINT fk_8838e48da76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
