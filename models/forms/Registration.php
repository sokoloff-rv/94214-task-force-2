<?php
namespace app\models\forms;

use yii\base\Model;

class Registration extends Model
{
    public string $name = '';
    public string $email = '';
    public string $city = '';
    public string $password = '';
    public string $passwordRepeat = '';
    public bool $isExecutor = false;

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city' => 'Город',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля',
            'isExecutor' => 'Я собираюсь откликаться на задания'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'email', 'city', 'password', 'passwordRepeat', 'isExecutor'], 'safe'],
            [['name', 'email', 'city', 'password', 'passwordRepeat'], 'required'],
            ['email', 'email'],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
            [['isExecutor'], 'boolean']
        ];
    }
}
