<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

/**
 * Класс для действия "Отказаться" в рамках задачи.
 */
class ActionDeny extends ActionAbstract
{
    /**
     * Возвращает имя действия.
     *
     * @return string Имя действия.
     */
    public static function getName(): string
    {
        return 'deny';
    }

    /**
     * Возвращает название действия.
     *
     * @return string Название действия.
     */
    public static function getTitle(): string
    {
        return 'Отказаться';
    }

    /**
     * Проверяет право на выполнение действия "Отказаться".
     *
     * @param Task $task Объект задачи.
     * @param int $idCurrentUser Идентификатор текущего пользователя.
     * @return bool Возвращает true, если действие разрешено, иначе false.
     */
    public function checkRight(Task $task, int $idCurrentUser): bool
    {
        return
        $task->currentStatus === Task::STATUS_WORKING
        && $idCurrentUser === $task->getIdExecutor();
    }
}
