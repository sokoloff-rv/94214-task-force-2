<?php

namespace app\models\forms;

use app\models\User;
use app\models\Category;
use Yii;
use yii\base\Model;

class EditProfileForm extends Model
{

    public string $avatar = '';
    public string $name = '';
    public string $email = '';
    public string $birthday = '';
    public string $phone = '';
    public string $telegram = '';
    public string $information = '';
    public array $specializations = [];

    public function attributeLabels(): array
    {
        return [
            'avatar' => 'Аватар',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'birthday' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'information' => 'Информация о себе',
            'specializations' => 'Выбор специализаций',
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            [['email'], 'unique'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['phone'], 'match', 'pattern' => '/^\d{11}$/', 'message' => 'Номер телефона должен состоять из 11 цифр'],
            [['telegram'], 'string', 'max' => 64],
            [['avatar', 'information', 'specializations'], 'safe'],
        ];
    }
}
