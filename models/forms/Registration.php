<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use app\models\City;

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
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email']],
            [['city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city' => 'id']],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
            [['isExecutor'], 'boolean']
        ];
    }

    public function newUser()
    {
        $user = new User;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->city_id = $this->city;
        $user->role = $this->isExecutor ? User::EXECUTOR : User::CUSTOMER;
        return $user;
    }
}
