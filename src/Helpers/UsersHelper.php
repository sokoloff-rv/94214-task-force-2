<?php

namespace Taskforce\Helpers;

use app\models\User;

class UsersHelper
{
    /**
     * Проверяет, может ли текущий пользователь видеть контакты пользователя, профиль которого он просматривает. Контакты видны всем, если пользователь этого не запретил. Если запретил, то контакты видны самому пользователю, а также всем заказчикам, с которыми он работает или работал по другим заданиям.
     *
     * @param int $userId ID пользователя, контакты которого текущий пользователь пытается посмотреть.
     * @param bool $contactsIsHidden Показывает, запретил ли пользователь просматривать свои контакты (true - запретил, false - не запрещал).
     * @return bool Возвращает true, если текущий пользователь может видеть контакты пользователя, на чьей странице профиля он находится, иначе false.
     */
    public static function userCanSeeContacts(int $userId, bool $contactsIsHidden): bool
    {
        if (!$contactsIsHidden) {
            return true;
        }

        $currentUser = User::getCurrentUser();
        if ($userId === $currentUser->id) {
            return true;
        }

        $executor = User::findOne($userId);
        $commonTasks = $executor->getExecutorTasks()->where(['customer_id' => $currentUser->id])->all();
        if (!empty($commonTasks)) {
            return true;
        }

        return false;
    }
}
