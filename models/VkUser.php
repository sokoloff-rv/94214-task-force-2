<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\City;

class VkUser extends Model
{
    public function createUser($userData)
    {
        $user = new User;
        $user->name = $userData['first_name'] . ' ' . $userData['last_name'];
        $user->email = $userData['email'];
        $birthayDate = \DateTime::createFromFormat('d.m.Y', $userData['bdate']);
        $user->birthday = $birthayDate ? $birthayDate->format('Y-m-d') : null;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash('password');
        $user->city_id = City::getIdByName($userData['city']['title']);
        $user->vk_id = $userData['user_id'];
        $user->avatar = $userData['photo'];
        $user->save(false);

        Yii::$app->user->login($user);
    }
}
