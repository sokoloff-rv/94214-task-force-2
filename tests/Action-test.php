<?php
require_once '../vendor/autoload.php';
use Taskforce\Models\Task;
use Taskforce\Actions\ActionCancel;
use Taskforce\Actions\ActionAccept;
use Taskforce\Actions\ActionRespond;
use Taskforce\Actions\ActionDeny;

header("Content-Type: text/plain");

$idCustomer = 1;
$idExecutor = 2;

$task = new Task($idCustomer, $idExecutor);
$cancel = new ActionCancel;
$accept = new ActionAccept;
$respond = new ActionRespond;
$deny = new ActionDeny;

// Проверяем метод получения названия класса
print_r($cancel->getName()."\n");

// Проверяем метод получения внутреннего имени класса
print_r($cancel->getTitle()."\n\n");

// Проверяем методы проверки прав
var_dump($cancel->checkRight($task, 1));
var_dump($cancel->checkRight($task, 2));
var_dump($cancel->checkRight($task, 3));
print_r("\n");

var_dump($accept->checkRight($task, 1));
var_dump($accept->checkRight($task, 2));
var_dump($accept->checkRight($task, 3));
print_r("\n");

var_dump($respond->checkRight($task, 1));
var_dump($respond->checkRight($task, 2));
var_dump($respond->checkRight($task, 3));
print_r("\n");

var_dump($deny->checkRight($task, 1));
var_dump($deny->checkRight($task, 2));
var_dump($deny->checkRight($task, 3));
print_r("\n-------\n\n");

$task->currentStatus = $task::STATUS_WORKING;

var_dump($cancel->checkRight($task, 1));
var_dump($cancel->checkRight($task, 2));
var_dump($cancel->checkRight($task, 3));
print_r("\n");

var_dump($accept->checkRight($task, 1));
var_dump($accept->checkRight($task, 2));
var_dump($accept->checkRight($task, 3));
print_r("\n");

var_dump($respond->checkRight($task, 1));
var_dump($respond->checkRight($task, 2));
var_dump($respond->checkRight($task, 3));
print_r("\n");

var_dump($deny->checkRight($task, 1));
var_dump($deny->checkRight($task, 2));
var_dump($deny->checkRight($task, 3));
print_r("\n-------\n\n");

$task->currentStatus = $task::STATUS_COMPLETED;

var_dump($cancel->checkRight($task, 1));
var_dump($cancel->checkRight($task, 2));
var_dump($cancel->checkRight($task, 3));
print_r("\n");

var_dump($accept->checkRight($task, 1));
var_dump($accept->checkRight($task, 2));
var_dump($accept->checkRight($task, 3));
print_r("\n");

var_dump($respond->checkRight($task, 1));
var_dump($respond->checkRight($task, 2));
var_dump($respond->checkRight($task, 3));
print_r("\n");

var_dump($deny->checkRight($task, 1));
var_dump($deny->checkRight($task, 2));
var_dump($deny->checkRight($task, 3));
