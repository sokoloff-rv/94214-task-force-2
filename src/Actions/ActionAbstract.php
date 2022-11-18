<?php
namespace Taskforce\Actions;

use Taskforce\Models\Task;

abstract class ActionAbstract
{
    abstract public static function getName();
    abstract public static function getTitle();
    abstract public function checkRight(Task $task, int $idCurrentUser);
}
