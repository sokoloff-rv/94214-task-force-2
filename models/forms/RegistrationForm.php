<?php
namespace app\models\forms;

use app\models\City;
use app\models\User;
use Yii;
use yii\base\Model;

class RegistrationForm extends Model
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
            'isExecutor' => 'Я собираюсь откликаться на задания',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'email', 'city', 'password', 'passwordRepeat'], 'required'],
            ['email', 'email'],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email']],
            [['city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city' => 'id']],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
            [['isExecutor'], 'boolean'],
        ];
    }

    public function newUser()
    {
        $user = new User;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->city_id = $this->city;
        $user->role = $this->isExecutor ? User::ROLE_EXECUTOR : User::ROLE_CUSTOMER;
        return $user;
    }

    public function createUser()
    {
        if ($this->validate()) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
            $this->newUser()->save(false);
            Yii::$app->response->redirect(['tasks']);
        }
    }
}
