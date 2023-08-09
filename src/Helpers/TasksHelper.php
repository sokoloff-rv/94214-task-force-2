<?php

namespace Taskforce\Helpers;

use app\models\User;
use Taskforce\Models\Task as TaskBasic;

class TasksHelper
{
    /**
     * Метод проверяет, может ли пользователь увидеть кнопку отклика на задание.
     *
     * @param int $userId идентификатор пользователя
     * @param string $userRole роль пользователя
     * @param string $taskStatus статус задания
     * @param array $responses массив откликов на задание
     * @return bool true, если пользователь может увидеть кнопку отклика, false - в противном случае.
     */
    public static function userCanSeeResponseButton(int $userId, string $userRole, string $taskStatus, array $responses): bool
    {
        if ($userRole === User::ROLE_EXECUTOR && $taskStatus === TaskBasic::STATUS_NEW) {
            foreach ($responses as $response) {
                if ($userId === $response->executor_id) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Метод проверяет, может ли пользователь увидеть кнопку отказа от задания.
     *
     * @param int $userId идентификатор пользователя
     * @param string $taskStatus статус задания
     * @param int|null $executorId идентификатор исполнителя задания
     * @return bool true, если пользователь может увидеть кнопку отказа, false - в противном случае.
     */
    public static function userCanSeeRefusalButton(int $userId, string $taskStatus, ?int $executorId): bool
    {
        return $userId === $executorId && $taskStatus === TaskBasic::STATUS_WORKING;
    }

    /**
     * Метод проверяет, может ли пользователь увидеть кнопку завершения задания.
     *
     * @param int $userId идентификатор пользователя
     * @param string $taskStatus статус задания
     * @param int|null $customerId идентификатор заказчика задания
     * @return bool true, если пользователь может увидеть кнопку завершения, false - в противном случае.
     */
    public static function userCanSeeCompletionButton(int $userId, string $taskStatus, ?int $customerId): bool
    {
        return $userId === $customerId && $taskStatus === TaskBasic::STATUS_WORKING;
    }

    /**
     * Метод проверяет, может ли пользователь увидеть кнопку отмены задания.
     *
     * @param int $userId идентификатор пользователя
     * @param string $taskStatus статус задания
     * @param int|null $customerId идентификатор заказчика задания
     * @return bool true, если пользователь может увидеть кнопку отмены, false - в противном случае.
     */
    public static function userCanSeeCancelButton(int $userId, string $taskStatus, ?int $customerId): bool
    {
        return $userId === $customerId && $taskStatus === TaskBasic::STATUS_NEW;
    }
}
