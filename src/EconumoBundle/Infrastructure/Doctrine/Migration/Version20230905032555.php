<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905032555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $sql = <<<SQL
INSERT INTO transactions (id, user_id, type, account_id, account_recipient_id, amount, amount_recipient, category_id, description, payee_id, tag_id, created_at, updated_at, spent_at)
SELECT gen_random_uuid(), tmp1.user_id, CASE WHEN COALESCE(transactions_balance, 0) + balance < 0 THEN 0 ELSE 1 END as type, tmp1.account_id, NULL, ABS(tmp1.missing_transaction), NULL, NULL, 'Econumo: Start balance', NULL, NULL, '2021-01-01 00:00:00', '2021-01-01 00:00:00', '2021-01-01 00:00:00'
FROM (SELECT * FROM (SELECT a.user_id as user_id,
             a.id as account_id,
             a.balance as balance,
             a.name,
             COALESCE(expenses, 0) + COALESCE(transfer_expenses, 0) - COALESCE(incomes, 0) - COALESCE(transfer_incomes, 0) as transactions_balance,
             COALESCE(expenses, 0) + COALESCE(transfer_expenses, 0) - COALESCE(incomes, 0) - COALESCE(transfer_incomes, 0) + balance as missing_transaction,
             a.created_at as created_at
      FROM accounts a
               LEFT JOIN (
                   SELECT tmp.account_id, SUM(tmp.expenses) as expenses, SUM(tmp.incomes) as incomes, SUM(tmp.transfer_expenses) as transfer_expenses, SUM(tmp.transfer_incomes) as transfer_incomes FROM (
                        SELECT tr1.account_id,
                               (SELECT SUM(t1.amount) FROM transactions t1 WHERE t1.account_id = tr1.account_id AND t1.type = 0) as expenses,
                               (SELECT SUM(t2.amount) FROM transactions t2 WHERE t2.account_id = tr1.account_id AND t2.type = 1) as incomes,
                               (SELECT SUM(t3.amount) FROM transactions t3 WHERE t3.account_id = tr1.account_id AND t3.type = 2) as transfer_expenses,
                               NULL as transfer_incomes
                        FROM transactions tr1
                        GROUP BY tr1.account_id
                        UNION ALL
                        SELECT tr2.account_recipient_id as account_id,
                               NULL as expenses,
                               NULL as incomes,
                               NULL as transfer_expenses,
                               (SELECT SUM(t4.amount_recipient) FROM transactions t4 WHERE t4.account_recipient_id = tr2.account_recipient_id AND t4.type = 2) as transfer_incomes
                        FROM transactions tr2
                        WHERE tr2.account_recipient_id IS NOT NULL
                        GROUP BY tr2.account_recipient_id) tmp GROUP BY tmp.account_id
               ) t ON a.id = t.account_id) as tmp2
               WHERE missing_transaction <> 0) as tmp1;
SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM transactions WHERE description = \'Econumo: Start balance\'');
    }
}
