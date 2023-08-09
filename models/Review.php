<?php

namespace app\models;

use Yii;

/**
 * Класс модели для таблицы "reviews" в базе данных.
 *
 * @property int $id Идентификатор отзыва.
 * @property int $customer_id Идентификатор заказчика.
 * @property int $executor_id Идентификатор исполнителя.
 * @property int $task_id Идентификатор задачи.
 * @property string|null $comment Комментарий к отзыву.
 * @property int|null $grade Оценка, выставленная исполнителю.
 * @property string|null $creation_date Дата создания отзыва.
 *
 * @property User $customer Заказчик, оставивший отзыв.
 * @property User $executor Исполнитель, которому адресован отзыв.
 * @property Task $task Задача, по которой оставлен отзыв.
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных.
     *
     * @return string Имя таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'reviews';
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'executor_id', 'task_id'], 'required'],
            [['customer_id', 'executor_id', 'task_id', 'grade'], 'integer'],
            [['comment'], 'string'],
            [['creation_date'], 'safe'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
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
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'task_id' => 'Task ID',
            'comment' => 'Comment',
            'grade' => 'Grade',
            'creation_date' => 'Creation Date',
        ];
    }

    /**
     * Получает запрос для [[Customer]].
     *
     * @return \yii\db\ActiveQuery Запрос для пользователя-заказчика.
     */
    public function getCustomer(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Получает запрос для [[Executor]].
     *
     * @return \yii\db\ActiveQuery Запрос для пользователя-исполнителя.
     */
    public function getExecutor(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Получает запрос для [[Task]].
     *
     * @return \yii\db\ActiveQuery Запрос для задачи, по которой оставлен отзыв.
     */
    public function getTask(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
