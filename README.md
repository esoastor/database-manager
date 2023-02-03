## Example ##

Sqlite & Mysql now

```
    require_once __DIR__ . '/vendor/autoload.php';


    use SqliteOrm\Schema\Blueprint;
    use SqliteOrm\Schema\SqliteConstructor;

    $blueprint = new Blueprint();

    $constructor = new SqliteConstructor();

    $constructor->createTable('test', [
        $blueprint->id(),
        $blueprint->text('login')->length(50)->notNull(),
        $blueprint->integer('number')->notNull(),
    ]);

    $test = $constructor->getDatabase('test');

    $insertData = [
        ['name' => 'Robert', 'surename' => 'Wolders', 'age' => '57'],
        ['name' => 'Jan', 'surename' => 'Vercauteren', 'age' => '51'],
        ['name' => 'Rutger', 'surename' => 'Hauer', 'age' => '61'],
        ['name' => 'Herbert', 'surename' => 'West', 'age' => '47']
    ];
    
    foreach ($insertData as $row) {
        $this->table->insert($row)->execute();
    }

    $test->count()->execute();
    $test->count()->where('surename', '=', 'Vercauteren')->where('age', '>', '0')->execute();

    $test->select(['name', 'age'])->execute();

    $test->update(['name' => 'aaa'])->where('name', '=', 'Abaddon')->execute();

    $test->delete()->where('surename', '=', 'Vercauteren')->execute();

```
### Tests ###

run docker-compose-unittest.yml, check logs of database_manager