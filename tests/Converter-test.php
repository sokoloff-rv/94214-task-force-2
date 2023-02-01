<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Taskforce\Utils\ConverterCSV;

$categories = new ConverterCSV(
    __DIR__ . "/../data/categories.csv",
    ['name', 'alias']
);
$categories->generateSqlFile('categories');

$cities = new ConverterCSV(
    __DIR__ . "/../data/cities.csv",
    ['name', 'latitude', 'longtitude']
);
$cities->generateSqlFile('cities');
