<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Класс формы редактирования профиля пользователя.
 */
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

    /**
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
     */
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

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['phone'], 'match', 'pattern' => '/^\d{11}$/', 'message' => 'Номер телефона должен состоять из 11 цифр'],
            [['telegram'], 'string', 'max' => 64],
            [['avatar', 'information', 'specializations'], 'safe'],
            [['avatar', 'name', 'email', 'birthday', 'phone', 'telegram', 'information'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * Сохраняет профиль пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return bool Возвращает true, если профиль успешно сохранен, иначе false.
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

        $user->name = $this->name;
        $user->email = $this->email;
        $user->birthday = $this->birthday;
        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->information = $this->information;

        if (!empty($this->specializations)) {
            $user->specializations = implode(', ', $this->specializations);
        }

        $newAvatar = UploadedFile::getInstance($this, 'avatar');
        if ($newAvatar) {
            $avatarPath = 'uploads/avatars/' .
            $userId . '_' . uniqid('upload') . '.' .
            $newAvatar->getExtension();
            $newAvatar->saveAs($avatarPath);
            $user->avatar = '/' . $avatarPath;
        }

        return $user->save();
    }
}
