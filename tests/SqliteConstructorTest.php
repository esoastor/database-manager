<?php

use Database\Database;
use Database\WhereBuilder;
use Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;
use Database\Schema\SqliteConstructor;
use Database\Schema\Fields\Base;
use Database\TableManager;

final class SqliteConstructorTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->testTableName = 'test_table';
        $this->databaseName = 'test.sqlite';
        
        $this->blueprint = new Blueprint();
        $this->constructor = new SqliteConstructor($this->databaseName);
        $this->fields = [
            $this->blueprint->id(),
            $this->blueprint->text('name'),
            $this->blueprint->text('surename'),
            $this->blueprint->number('age'),
        ];
    }

    public function testAssertSqliteConstructorInstance(): void
    {
        $this->assertInstanceOf(SqliteConstructor::class, $this->constructor);
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
    public function testAssertWhereBuilderInstance(): void
    {
        $this->assertInstanceOf(WhereBuilder::class, $this->constructor->getDatabase()->table($this->testTableName)->where('1', '2', '3'));
    }

    public function testTableCreation(): void
    {
        $this->expectNotToPerformAssertions();
        $this->constructor->createTable($this->testTableName, $this->fields);
    }
}