<?php

require_once __DIR__ . '/TestRunner.php';

$runner = new \Tests\TestRunner();
$report = $runner->run();
