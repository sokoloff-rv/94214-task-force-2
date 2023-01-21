<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionDeny extends ActionAbstract
{
    public static function getName(): string
    {
        return 'deny';
    }

    public static function getTitle(): string
    {
        return 'Отказаться';
    }

    public function checkRight(Task $task, int $idCurrentUser): bool
    {
        return
        $task->currentStatus === Task::STATUS_WORKING
        && $idCurrentUser === $task->getIdExecutor();
    }
}
