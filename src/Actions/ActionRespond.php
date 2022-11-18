<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionRespond extends ActionAbstract
{
    public static function getName()
    {
        return 'respond';
    }
    public static function getTitle()
    {
        return 'Откликнуться';
    }

    public function checkRight(Task $task, int $idCurrentUser)
    {
        return
        $task->currentStatus === Task::STATUS_NEW
        && $idCurrentUser === $task->getIdExecutor();
    }
}
