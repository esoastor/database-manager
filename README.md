## Example ##

```
    require_once __DIR__ . '/vendor/autoload.php';


    use SqliteOrm\Schema\Blueprint;
    use SqliteOrm\Schema\Constructor;

    $blueprint = new Blueprint();

    $constructor = new Constructor();

    $constructor->createTable('test', [
        $blueprint->id(),
        $blueprint->text('login')->length(50)->notNull(),
        $blueprint->number('number')->notNull(),
    ]);

    $test = $constructor->getTableConnection('test');

    $test->insert(['login' => 'aaa', 'number' => 12]);
```