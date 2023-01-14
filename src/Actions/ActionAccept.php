<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

class ActionAccept extends ActionAbstract
{
    public static function getName(): string
    {
        return 'accept';
    }

    public static function getTitle(): string
    {
        return 'Принять';
    }

    public function checkRight(Task $task, int $idCurrentUser): bool
    {
        return
        $task->currentStatus === Task::STATUS_WORKING
        && $idCurrentUser === $task->getIdCustomer();
    }
}
