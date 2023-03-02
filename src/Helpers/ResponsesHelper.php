<?php
namespace Taskforce\Helpers;

use Taskforce\Models\Response;

class ResponsesHelper
{
    /**
     * Проверяет, может ли пользователь просматривать список откликов на задание.
     * @param array $responses массив откликов на задание
     * @param int $user_id идентификатор пользователя
     * @param int $customer_id идентификатор заказчика задания
     * @return bool true, если пользователь может просмотреть список, false - в противном случае.
     */
    public static function userCanSeeResponsesList(array $responses, int $user_id, int $customer_id): bool {
        if ($responses) {
            if ($user_id === $customer_id) {
                return true;
            }
            foreach ($responses as $response) {
                if ($user_id === $response->executor_id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Проверяет, может ли пользователь просматривать отклик на задание.
     * @param int $user_id идентификатор пользователя
     * @param int $customer_id идентификатор заказчика задания
     * @param int $executor_id идентификатор исполнителя, оставившего отклик на задание
     * @return bool true, если пользователь может просмотреть отклик, false - в противном случае.
     */
    public static function userCanSeeResponse(int $user_id, int $customer_id, int $executor_id): bool {
        return $user_id === $customer_id || $user_id === $executor_id;
    }
}
