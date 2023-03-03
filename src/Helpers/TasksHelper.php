<?php

namespace Taskforce\Helpers;

use app\models\User;
use Taskforce\Models\Task as TaskBasic;

class TasksHelper
{
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

    public static function userCanSeeRefusalButton(int $userId, string $taskStatus, ?int $executorId): bool
    {
        return $userId === $executorId && $taskStatus === TaskBasic::STATUS_WORKING;
    }
}
