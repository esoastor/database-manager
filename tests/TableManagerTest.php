<?php

use Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;
use Database\Schema\SqliteConstructor;

final class TableManagerTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->testTableName = 'test_table';
        $this->databaseName = 'test.sqlite';

        $this->blueprint = new Blueprint();
        $this->constructor = new SqliteConstructor('test.sqlite');

        $fields = ['id', 'name', 'surename', 'age'];
    }

    public function setUp(): void
    {
        $this->table = $this->constructor->getDatabase()->table($this->testTableName);
    }

    /**
     * @dataProvider validInsertValues
     */
    public function testInsert(mixed $value): void
    {
        $this->expectNotToPerformAssertions();
        $this->table->insert($value);
    }

    public function validInsertValues(): array
    {
        return [
            [
                ['name' => 'Abaddon', 'surename' => 'Hersen', 'age' => 57], 
            ],
            [
                ['name' => 'Jan', 'surename' => 'Vercauteren', 'age' => 51], 
            ],
            [
                ['name' => 'Heller', 'surename' => 'Match','age' => 61], 
            ],
            [
                ['name' => 'Herbert', 'surename' => 'Hosen','age' => 47],
            ],         
        ];
    }
}