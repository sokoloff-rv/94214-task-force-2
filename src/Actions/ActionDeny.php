<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionDeny extends ActionAbstract
{
    public static function getName()
    {
        return 'deny';
    }
    public static function getTitle()
    {
        return 'Отказаться';
    }

    public function checkRight(Task $task, int $idCurrentUser)
    {
        return
        $task->currentStatus === Task::STATUS_WORKING
        && $idCurrentUser === $task->getIdExecutor();
    }
}
