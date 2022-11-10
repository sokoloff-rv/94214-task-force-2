<?php
header("Content-Type: text/plain");
require_once '../src/Models/Task.php';

$idCustomer = 1;
$idExecutor = 1;

$task = new Task($idCustomer, $idExecutor);

// получаем список всех возможных статусов
print_r($task->getStatusesMap());

//получаем список всех возможных действий
print_r($task->getActionsMap());

// получаем текущий статус
print_r($task->currentStatus."\n");

// получаем следующий статус задания в ответ на действие
print_r($task->getNextStatus('cancel')."\n");

// получаем список доступных действий для задания в определенном статусе
print_r($task->getAvailableActions('new'));
