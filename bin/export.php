#!/usr/bin/env php
<?php

ini_set('memory_limit', '128M');
set_time_limit(-1);

$rootDir = __DIR__.'/..';

// autoloader
$loader = require $rootDir.'/vendor/autoload.php';
$loader->addPsr4('App\\', $rootDir.'/src');

// get config
$configFile = \App\Helper::checkCopiedDist($rootDir.'/config.ini');
$config = parse_ini_file($configFile);

// load objects
$requester = new \App\Requester($config);
$exporter = new \App\Exporter($requester);
$processor = new \App\Processor($exporter, $rootDir);

// start processing
$processor->start('activities');
$processor->start('features');
$processor->start('items');
$processor->start('pools');
$processor->start('questions');
$processor->start('sessions');
$processor->start('tags');
