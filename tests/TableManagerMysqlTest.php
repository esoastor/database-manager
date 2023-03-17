<?php

use PHPUnit\Framework\TestCase;
use Database\Schema\Mysql\MysqlConstructor;

final class TableManagerMysqlTest extends TestCase
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
        $this->constructor->dropTable($this->testTableName);
        $this->blueprint =  $this->constructor->getBlueprintBuilder();
        $this->fields = [
            $this->blueprint->id(),
            $this->blueprint->varchar('name'),
            $this->blueprint->varchar('surename'),
            $this->blueprint->tinyInteger('age'),
        ];
        $this->constructor->createTable($this->testTableName, $this->fields);
    }

    public function setUp(): void
    {
        $this->table = $this->constructor->getDatabase()->table($this->testTableName);
    }

    public function testInsertAndCount(): void
    {
        $insertData = [
            ['name' => 'Robert', 'surename' => 'Wolders', 'age' => '57'],
            ['name' => 'Jan', 'surename' => 'Vercauteren', 'age' => '51'],
            ['name' => 'Rutger', 'surename' => 'Hauer', 'age' => '61'],
            ['name' => 'Herbert', 'surename' => 'West', 'age' => '47']
        ];
        foreach ($insertData as $row) {
            $this->table->insert($row)->execute();
        }

        $this->assertEquals($this->table->count()->execute(), count($insertData));
    }

    public function testWhere(): void
    {
        $this->assertEquals($this->table->count()->where('age', '>', '50')->execute(), 3);
        $this->assertEquals($this->table->count()->where('name', '=', 'Jan')->execute(), 1);
        $this->assertEquals($this->table->count()->where('name', 'Jan')->execute(), 1);
        $this->assertEquals($this->table->count()->where('surename', '=', 'Vercauteren')->execute(), 1);
        $this->assertEquals($this->table->count()->where('surename', '=', 'Vercauteren')->where('age', '>', '0')->execute(), 1);
        $this->assertEquals($this->table->count()->where('surename', 'Vercauteren')->where('age', '>', '0')->execute(), 1);
        $this->assertEquals($this->table->count()->where('name', '=', 'Jan')->where('surename', '=', 'Vercauteren')->where('age', '=', '51')->execute(), 1);
        $this->assertEquals($this->table->count()->where('name', '=', 'Jan')->where('age', '>', '80')->execute(), 0);
    }

    public function testWhereIn(): void
    {
        $this->assertEquals($this->table->count()->whereIn('name', ['Robert', 'Jan'])->execute(), 2);
        $this->assertEquals($this->table->count()->whereIn('name', ['Jan', 'Hasbulla'])->whereIn('age', ['11', '51'])->execute(), 1);
        $this->assertEquals($this->table->count()->whereIn('name', ['Sam', 'Hasbulla'])->execute(), 0);
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

    /**
     * @depends testInsertAndCount
     */
    public function testUpdate(): void
    {
        $this->table->update(['name' => 'aaa'])->where('name', '=', 'Herbert')->execute();
        $this->assertEquals($this->table->count()->where('name', '=', 'aaa')->execute(), 1);

        $this->table->update(['name' => 'bbb'])->where('age', '>', '0')->execute();
        $this->assertEquals($this->table->count()->where('name', '=', 'bbb')->execute(), 4);
    }

    /**
     * @depends testUpdate
     */
    public function testDelete(): void
    {
        $this->table->delete()->where('surename', '=', 'Vercauteren')->execute();
        $this->assertEquals($this->table->count()->where('surename', '=', 'Vercauteren')->execute(), 0);

        $this->table->delete()->where('age', '>', '0')->execute();
        $this->assertEquals($this->table->count()->where('age', '>', '0')->execute(), 0);
    }
}
