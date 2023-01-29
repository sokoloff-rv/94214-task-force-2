<?php
require_once '../vendor/autoload.php';
use Taskforce\Utils\ConverterCSV;

$categories = new ConverterCSV(
    "../data/categories.csv",
    ['name', 'icon']
);
$categories->import();
var_dump($categories->getData());
