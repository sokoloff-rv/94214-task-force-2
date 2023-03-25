<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Класс модели для таблицы "responses" в базе данных.
 *
 * @property int $id Идентификатор отклика.
 * @property int $executor_id Идентификатор исполнителя.
 * @property int $task_id Идентификатор задачи.
 * @property string|null $comment Комментарий к отклику.
 * @property int|null $price Цена, предложенная исполнителем.
 * @property string|null $creation_date Дата создания отклика.
 * @property string $status Статус отклика.
 *
 * @property User $executor Исполнитель, оставивший отклик.
 * @property Task $task Задача, на которую оставлен отклик.
 */
class Response extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACCEPTED = 'accepted';

    /**
     * Принимает отклик.
     *
     * @return bool Успешность сохранения изменений.
     */
    public function accept(): bool
    {
        $this->status = self::STATUS_ACCEPTED;
        return $this->save();
    }

    /**
     * Отклоняет отклик.
     *
     * @return bool Успешность сохранения изменений.
     */
    public function reject(): bool
    {
        $this->status = self::STATUS_REJECTED;
        return $this->save();
    }

    /**
     * Возвращает имя таблицы в базе данных.
     *
     * @return string Имя таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'responses';
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['executor_id', 'task_id'], 'required'],
            [['executor_id', 'task_id', 'price'], 'integer'],
            [['comment'], 'string'],
            [['creation_date'], 'safe'],
            [['status'], 'string', 'max' => 50],
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
            'executor_id' => 'Executor ID',
            'task_id' => 'Task ID',
            'comment' => 'Comment',
            'price' => 'Price',
            'creation_date' => 'Creation Date',
            'status' => 'Status',
        ];
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
     * @return \yii\db\ActiveQuery Запрос для задачи, на которую оставлен отклик.
     */
    public function getTask(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
