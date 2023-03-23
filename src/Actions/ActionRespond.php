<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

/**
 * Класс для действия "Откликнуться" в рамках задачи.
 */
class ActionRespond extends ActionAbstract
{
    /**
     * Возвращает имя действия.
     *
     * @return string Имя действия.
     */
    public static function getName(): string
    {
        return 'respond';
    }

    /**
     * Возвращает название действия.
     *
     * @return string Название действия.
     */
    public static function getTitle(): string
    {
        return 'Откликнуться';
    }

    /**
     * Проверяет право на выполнение действия "Откликнуться".
     *
     * @param Task $task Объект задачи.
     * @param int $idCurrentUser Идентификатор текущего пользователя.
     * @return bool Возвращает true, если действие разрешено, иначе false.
     */
    public function checkRight(Task $task, int $idCurrentUser): bool
    {
        return
        $task->currentStatus === Task::STATUS_NEW
        && $idCurrentUser === $task->getIdExecutor();
    }
}
