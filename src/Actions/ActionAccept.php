<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionAccept extends ActionAbstract
{
    public static function getName()
    {
        return 'accept';
    }
    public static function getTitle()
    {
        return 'Принять';
    }

    public function checkRight(Task $task, int $idCurrentUser)
    {
        return
        $task->currentStatus === Task::STATUS_WORKING
        && $idCurrentUser === $task->getIdCustomer();
    }
}
