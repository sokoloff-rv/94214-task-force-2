<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionRespond extends ActionAbstract
{
    public static function getName(): string
    {
        return 'respond';
    }

    public static function getTitle(): string
    {
        return 'Откликнуться';
    }

    public function checkRight(Task $task, int $idCurrentUser): bool
    {
        return
        $task->currentStatus === Task::STATUS_NEW
        && $idCurrentUser === $task->getIdExecutor();
    }
}
