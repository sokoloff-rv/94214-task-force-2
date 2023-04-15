<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

/**
 * Класс формы настроек безопасности профиля пользователя.
 */
class SecureProfileForm extends Model
{
    public string $oldPassword = '';
    public string $newPassword = '';
    public string $repeatPassword = '';
    public bool $hiddenContacts = false;

    /**
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
     */
    public function attributeLabels(): array
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'repeatPassword' => 'Повтор нового пароля',
            'hiddenContacts' => 'Скрыть контакты для всех, кроме заказчика',
        ];
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            ['oldPassword', 'validateOldPassword'],
            ['newPassword', 'validateNewPassword', 'skipOnEmpty' => false],
            ['repeatPassword', 'compare', 'compareAttribute' => 'newPassword'],
            ['hiddenContacts', 'safe'],
            [['hiddenContacts'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * Валидация старого пароля.
     *
     * @param string $attribute Атрибут для валидации.
     * @param array $params Параметры валидации.
     */
    public function validateOldPassword($attribute, $params): void
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!$user || !Yii::$app->security->validatePassword($this->oldPassword, $user->password)) {
            $this->addError($attribute, 'Неправильный старый пароль');
        }
    }

    /**
     * Валидация нового пароля.
     *
     * @param string $attribute Атрибут для валидации.
     * @param array $params Параметры валидации.
     */
    public function validateNewPassword($attribute, $params): void
    {
        if (!empty($this->newPassword) && (empty($this->oldPassword))) {
            $this->addError('oldPassword', 'Пожалуйста, введите старый пароль');
        }
        if (empty($this->newPassword) && !empty($this->oldPassword)) {
            $this->addError($attribute, 'Пожалуйста, введите новый пароль');
        }
        if (!empty($this->newPassword) && empty($this->repeatPassword)) {
            $this->addError('repeatPassword', 'Пожалуйста, повторите новый пароль');
        }
    }

    /**
     * Сохраняет данные профиля пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return bool Результат сохранения данных профиля пользователя.
     */
    public function saveProfile(int $userId): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::findOne($userId);
        if (!$user) {
            return false;
        }

        if (!empty($this->newPassword)) {
            $user->password = Yii::$app->security->generatePasswordHash($this->newPassword);
        }

        $user->hidden_contacts = $this->hiddenContacts ? 1 : 0;

        return $user->save();
    }
}
