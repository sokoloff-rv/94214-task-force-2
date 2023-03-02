<?php
namespace Taskforce\Helpers;

use Taskforce\Models\Response;

class ResponsesHelper
{
    public static function userHadResponse(array $responses, int $executor_id): bool {
        foreach ($responses as $response) {
            if ($response->executor_id === $executor_id) {
                return true;
            }
        }
        return false;
    }
}
