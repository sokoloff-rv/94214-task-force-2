<?php
namespace Taskforce\Actions;

use Taskforce\Models\Task;

/**
 * Абстрактный класс для действий с задачами.
 */
abstract class ActionAbstract
{
    /**
     * Возвращает имя действия.
     *
     * @return string Имя действия.
     */
    abstract public static function getName();

    /**
     * Возвращает название действия.
     *
     * @return string Название действия.
     */
    abstract public static function getTitle();

    /**
     * Проверяет право на выполнение действия.
     *
     * @param Task $task Объект задачи.
     * @param int $idCurrentUser Идентификатор текущего пользователя.
     * @return bool Возвращает true, если действие разрешено, иначе false.
     */
    abstract public function checkRight(Task $task, int $idCurrentUser);
}
