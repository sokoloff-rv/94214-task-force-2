<?php
namespace Taskforce\Actions;

use Taskforce\Actions\ActionAbstract;
use Taskforce\Models\Task;

/**
 * Класс для действия "Отменить" в рамках задачи.
 */
class ActionCancel extends ActionAbstract
{
    /**
     * Возвращает имя действия.
     *
     * @return string Имя действия.
     */
    public static function getName(): string
    {
        return 'cancel';
    }

    /**
     * Возвращает название действия.
     *
     * @return string Название действия.
     */
    public static function getTitle(): string
    {
        return 'Отменить';
    }

    /**
     * Проверяет право на выполнение действия "Отменить".
     *
     * @param Task $task Объект задачи.
     * @param int $idCurrentUser Идентификатор текущего пользователя.
     * @return bool Возвращает true, если действие разрешено, иначе false.
     */
    public function checkRight(Task $task, int $idCurrentUser): bool
    {
        return
        $task->currentStatus === Task::STATUS_NEW
        && $idCurrentUser === $task->getIdCustomer();
    }
}
