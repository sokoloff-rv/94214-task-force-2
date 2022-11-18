<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionCancel extends ActionAbstract
{
    public static function getName()
    {
        return 'cancel';
    }
    public static function getTitle()
    {
        return 'Отменить';
    }

    public function checkRight(Task $task, int $idCurrentUser)
    {
        return
        $task->currentStatus === Task::STATUS_NEW
        && $idCurrentUser === $task->getIdCustomer();
    }
}
