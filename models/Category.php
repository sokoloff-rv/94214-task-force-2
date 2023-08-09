<?php

namespace app\models;

use Yii;

/**
 * Класс модели для таблицы "categories" в базе данных.
 *
 * @property int $id Идентификатор категории.
 * @property string $name Название категории.
 * @property string $alias Псевдоним категории.
 *
 * @property Task $tasks Задачи, связанные с данной категорией.
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных.
     *
     * @return string Имя таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'categories';
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['name', 'alias'], 'required'],
            [['name', 'alias'], 'string', 'max' => 100],
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
            'alias' => 'Alias',
        ];
    }

    /**
     * Получает запрос для [[Task]].
     *
     * @return \yii\db\ActiveQuery Запрос для задач, связанных с данной категорией.
     */
    public function getTasks(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Task::class, ['category_id' => 'id']);
    }

    /**
     * Возвращает название категории по ее идентификатору.
     *
     * @param int $id Идентификатор категории.
     * @return string Название категории.
     */
    public static function getCategoryName(int $id): string
    {
        return self::find()->select('name')->where(['id' => $id])->one()['name'];
    }
}
