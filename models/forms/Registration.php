<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }
}
