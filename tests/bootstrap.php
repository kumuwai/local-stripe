<?php

if (!is_file(dirname(__DIR__) . '/vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run test suite.');
}


require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/tests/TestCase.php';
require dirname(__DIR__) . '/tests/fixtures.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => ':memory:',
]);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->bootEloquent();
$capsule->setAsGlobal();

$resolver = $capsule->getDatabaseManager();
$repo = new DatabaseMigrationRepository($resolver, 'migrations');
$migrator = new Migrator($repo, $resolver, new Filesystem);
$schema = new SchemaBuilder($capsule->getConnection());
$seeder = new TestSeeder($capsule); // defined in tests/fixtures.php

$repo->createRepository();
$migrator->run(__DIR__.'/../src/database/migrations');
$seeder->run();

