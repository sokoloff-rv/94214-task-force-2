<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $link
 * @property int $task_id
 *
 * @property Task $task
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['link', 'task_id'], 'required'],
            [['task_id'], 'integer'],
            [['link'], 'string', 'max' => 255],
            [['link'], 'unique'],
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
            'link' => 'Link',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    public static function saveFile(string $link, int $taskId): void
    {
        $newFile = new self;
        $newFile->link = $link;
        $newFile->task_id = $taskId;
        $newFile->save(false);
    }
}
