<?php

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Application.php';

$app = new Application(array('config' => __DIR__ . '/../app/configs/application.php'));

// enable debug mode
$app['debug'] = true;

$app->run();