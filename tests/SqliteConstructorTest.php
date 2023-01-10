<?php

use Database\Database;
use Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;
use Database\Schema\SqliteConstructor;
use Database\Schema\Fields\Base;

final class SqliteConstructorTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        $this->testTableName = 'test';

        try {
            $this->blueprint = new Blueprint();
            $this->constructor = new SqliteConstructor('test.sqlite');
            $this->fields = [
                $this->blueprint->id(),
                $this->blueprint->text('title'),
                $this->blueprint->number('status'),
            ];
        } catch (\Throwable $error)
        {
            echo "here";
        }
    }

    public function testAssertSqliteConstructorInstance(): void
    {
        $this->assertInstanceOf(SqliteConstructor::class, $this->constructor);
    }

    public function testAssertDatabaseInstance(): void
    {
        $this->assertInstanceOf(Database::class, $this->constructor->getDatabase());
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
     * @depends testAssertFieldInstance
     */
    public function testAssertFieldInstance(): void
    {
        foreach ($this->fields as $field) {
            $this->assertInstanceOf(Base\Field::class, $field);
        }
    }
}