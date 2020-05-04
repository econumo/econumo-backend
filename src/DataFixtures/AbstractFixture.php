<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Exception;

abstract class AbstractFixture extends Fixture
{
    /**
     * @var string
     */
    public $tableName;

    /**
     * @var string
     */
    public $dataFile;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Doctrine\DBAL\DBALException
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getDataForInsert() as $data) {
            $this->connection->insert(
                $this->getTableName(),
                $data
            );
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getTableName(): string
    {
        if (empty($this->tableName)) {
            throw new Exception('$tableName property must be set');
        }

        return $this->tableName;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getDataForInsert(): array
    {
        if (empty($this->dataFile)) {
            throw new Exception('$dataFile name property must be set');
        }

        if (file_exists($this->dataFile) === false) {
            throw new Exception(sprintf('File "%s" not exists', $this->dataFile));
        }

        return require($this->dataFile);
    }
}
