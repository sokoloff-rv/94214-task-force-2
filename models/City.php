<?php

namespace app\models;

use Yii;
use Taskforce\Exceptions\ExceptionData;

/**
 * Класс модели для таблицы "cities" в базе данных.
 *
 * @property int $id Идентификатор города.
 * @property string $name Название города.
 * @property float $latitude Широта города.
 * @property float $longitude Долгота города.
 *
 * @property Task $tasks Задачи, связанные с данным городом.
 * @property User $users Пользователи, связанные с данным городом.
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных.
     *
     * @return string Имя таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'cities';
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['name', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'latitude' => 'Latitude',
            'longitude' => 'longitude',
        ];
    }

    /**
     * Получает запрос для [[Task]].
     *
     * @return \yii\db\ActiveQuery Запрос для задач, связанных с данным городом.
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['city_id' => 'id']);
    }

    /**
     * Получает запрос для [[User]].
     *
     * @return \yii\db\ActiveQuery Запрос для пользователей, связанных с данным городом.
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['city_id' => 'id']);
    }

    /**
     * Возвращает идентификатор города по его названию.
     *
     * @param string $name Название города.
     * @return int Идентификатор города.
     * @throws ExceptionData Исключение при отсутствии города в базе данных.
     */
    public static function getIdByName($name)
    {
        $city = City::findOne(['name' => $name]);
        if (!$city) {
            throw new ExceptionData("Города $name нет в нашей базе!");
        }
        return $city->id;
    }
}
