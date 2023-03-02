<?php
namespace Taskforce\Helpers;

use Taskforce\Models\Response;

class ResponsesHelper
{
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

    public static function userCanSeeResponse(int $user_id, int $customer_id, int $executor_id): bool {
        if ($user_id === $customer_id || $user_id === $executor_id) {
            return true;
        }
        return false;
    }
}
