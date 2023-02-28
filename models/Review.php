<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $executor_id
 * @property int $task_id
 * @property string|null $comment
 * @property int|null $grade
 * @property string|null $creation_date
 *
 * @property Users $customer
 * @property Users $executor
 * @property Tasks $task
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
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
