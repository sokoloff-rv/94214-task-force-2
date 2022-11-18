<?php
require_once '../vendor/autoload.php';
use Taskforce\Models\Task;

header("Content-Type: text/plain");

$idCustomer = 1;
$idExecutor = 2;

$task = new Task($idCustomer, $idExecutor);

// получаем список всех возможных статусов
print_r($task->getStatusesMap());

//получаем список всех возможных действий
print_r($task->getActionsMap());

// получаем текущий статус
print_r($task->currentStatus."\n");

// получаем следующий статус задания в ответ на действие
print_r($task->getNextStatus(Task::ACTION_CANCEL)."\n");

// получаем список доступных действий для задания в определенном статусе
print_r($task->getAvailableActions(Task::STATUS_NEW));
