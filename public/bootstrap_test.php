<?php

require_once dirname(__DIR__) . '/public/bootstrap.php';

// Run the seeds
require dirname(__DIR__) . '/public/fixtures.php';
$seeder = new TestSeeder($capsule);
$seeder->run();

// Load the base TestCase class
require dirname(__DIR__) . '/tests/TestCase.php';
