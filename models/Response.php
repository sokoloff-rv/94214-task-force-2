<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responses".
 *
 * @property int $id
 * @property int $executor_id
 * @property int $task_id
 * @property string|null $comment
 * @property int|null $price
 * @property string|null $creation_date
 * @property string $status
 *
 * @property User $executor
 * @property Task $task
 */
class Response extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'responses';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
