<?php

namespace Taskforce\Helpers;

use app\models\Response;
use Taskforce\Models\Task as TaskBasic;

class ResponsesHelper
{
    /**
     * Проверяет, может ли пользователь просматривать список откликов на задание.
     * @param array $responses массив откликов на задание
     * @param int $userId идентификатор пользователя
     * @param int $customerId идентификатор заказчика задания
     * @return bool true, если пользователь может просмотреть список, false - в противном случае.
     */
    public static function userCanSeeResponsesList(array $responses, int $userId, int $customerId): bool {
        if ($responses) {
            if ($userId === $customerId) {
                return true;
            }
            foreach ($responses as $response) {
                if ($userId === $response->executor_id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Проверяет, может ли пользователь просматривать отклик на задание.
     * @param int $userId идентификатор пользователя
     * @param int $customerId идентификатор заказчика задания
     * @param int $executorId идентификатор исполнителя, оставившего отклик на задание
     * @return bool true, если пользователь может просмотреть отклик, false - в противном случае.
     */
    public static function userCanSeeResponse(int $userId, int $customerId, int $executorId): bool {
        return $userId === $customerId || $userId === $executorId;
    }

    /**
     * Проверяет, может ли пользователь просматривать кнопки в отклике на задание.
     * @param int $userId идентификатор пользователя
     * @param int $customerId идентификатор заказчика задания
     * @param string $taskStatus статус задания
     * @param string $responseStatus статус отклика на задание
     * @return bool true, если пользователь может просмотреть кнопки в отклике, false - в противном случае.
     */
    public static function userCanSeeResponseButtons(int $userId, int $customerId, string $taskStatus, string $responseStatus): bool {
        return $userId === $customerId && $responseStatus === Response::STATUS_NEW && $taskStatus === TaskBasic::STATUS_NEW;
    }
}
