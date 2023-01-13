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

    public function testInsertAndCount(): void
    {
        $insertData = [
            ['name' => 'Abaddon', 'surename' => 'Hersen', 'age' => '57'],
            ['name' => 'Jan', 'surename' => 'Vercauteren', 'age' => '51'],
            ['name' => 'Heller', 'surename' => 'Match', 'age' => '61'],
            ['name' => 'Herbert', 'surename' => 'Hosen', 'age' => '47']
        ];
        // foreach ($insertData as $row) {
        //     $this->table->insert($row);
        // }

        $this->assertEquals($this->table->count()->execute(), count($insertData));
        $this->assertEquals($this->table->count()->where('age', '>', '50')->execute(), 3);
        $this->assertEquals($this->table->count()->where('name', '=', 'Jan')->execute(), 1);
        $this->assertEquals($this->table->count()->where('surename', '=', 'Vercauteren')->execute(), 1);
        $this->assertEquals($this->table->count()->where('surename', '=', 'Vercauteren')->where('age', '>', '0')->execute(), 1);
        $this->assertEquals($this->table->count()->where('name', '=', 'Jan')->where('surename', '=', 'Vercauteren')->where('age', '=', '51')->execute(), 1);
        $this->assertEquals($this->table->count()->where('name', '=', 'Jan')->where('age', '>', '80')->execute(), 0);
    }

    /**
     * @depends testInsertAndCount
     */
    public function testSelect(): void
    {
        $this->assertEquals(count($this->table->select(['name'])->execute()), 4);
        $this->assertEquals(count($this->table->select(['name', 'age'])->execute()[0]), 2);
        $this->assertEquals(count($this->table->select()->execute()[0]), 4);
        $this->assertEquals(count($this->table->select()->where('age', '>', '50')->execute()), 3);
    }
}
