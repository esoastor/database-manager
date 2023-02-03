<?php

use Database\Database;
use Database\Query\BaseQuery;
use Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;
use Database\Schema\Mysql\MysqlConstructor;
use Database\Schema\Fields\Base;
use Database\TableManager;

final class MysqlConstructorTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->host = 'db:3306';
        $this->username = 'user'; 
        $this->password = 'aaa123';
        $this->databaseName = 'example';
        $this->testTableName = 'test_table';
        
        $this->constructor = new MysqlConstructor($this->host, $this->databaseName, $this->username, $this->password);
        $this->blueprint =  $this->constructor->getBlueprintBuilder();
        $this->fields = [
            $this->blueprint->id(),
            $this->blueprint->varchar('name'),
            $this->blueprint->varchar('surename'),
            $this->blueprint->tinyInteger('age'),
        ];
    }

    public function testAssertSqliteConstructorInstance(): void
    {
        $this->assertInstanceOf(MysqlConstructor::class, $this->constructor);
    }

    public function testAssertDatabaseInstance(): void
    {
        $this->assertInstanceOf(Database::class, $this->constructor->getDatabase());
    }

    public function testAssertTableManagerInstance(): void
    {
        $this->assertInstanceOf(TableManager::class, $this->constructor->getDatabase()->table($this->testTableName));
    }

    public function testAssertPdoInstance(): void
    {
        $this->assertInstanceOf(\PDO::class, $this->constructor->getPdoDriver());
    }

    public function testAssertBlueprintInstance(): void
    {
        $this->assertInstanceOf(Blueprint::class, $this->blueprint);
    }

    public function testAssertFieldInstance(): void
    {
        foreach ($this->fields as $field) {
            $this->assertInstanceOf(Base\Field::class, $field);
        }
    }

    /**
     * @depends testAssertTableManagerInstance
     */
    public function testAssertBaseQueryInstance(): void
    {
        $this->assertInstanceOf(BaseQuery::class, $this->constructor->getDatabase()->table($this->testTableName)->count());
    }

    public function testTableCreation(): void
    {
        $this->expectNotToPerformAssertions();
        $this->constructor->createTable($this->testTableName, $this->fields);
    }
}