<?php
require_once '../vendor/autoload.php';
use Taskforce\Utils\ConverterCSV;

$categories = new ConverterCSV(
    "../data/categories.csv",
    ['name', 'alias']
);
$categories->import();
$categories->generateSqlFile('categories');
