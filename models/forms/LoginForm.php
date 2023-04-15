<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Класс формы входа в систему.
 */
class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    private $_user;

    /**
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword'],
            [['email'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * Возвращает пользователя по email.
     *
     * @return User|null Возвращает экземпляр пользователя или null, если пользователь не найден.
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }

    /**
     * Проверяет корректность пароля.
     *
     * @param string $attribute Название атрибута, который валидируется.
     * @param array $params Дополнительные параметры, переданные в правило.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }
}
