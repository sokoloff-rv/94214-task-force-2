<?php

namespace app\models;

use Yii;

/**
 * Класс модели для таблицы "files" в базе данных.
 *
 * @property int $id Идентификатор файла.
 * @property string $link Ссылка на файл.
 * @property int $task_id Идентификатор задачи, к которой относится файл.
 *
 * @property Task $task Задача, к которой относится данный файл.
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных.
     *
     * @return string Имя таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'files';
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
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
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
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
     * Получает запрос для [[Task]].
     *
     * @return \yii\db\ActiveQuery Запрос для задачи, к которой относится данный файл.
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Сохраняет файл с заданными ссылкой и идентификатором задачи.
     *
     * @param string $link Ссылка на файл.
     * @param int $taskId Идентификатор задачи, к которой относится файл.
     * @return void
     */
    public static function saveFile(string $link, int $taskId): void
    {
        $newFile = new self;
        $newFile->link = $link;
        $newFile->task_id = $taskId;
        $newFile->save(false);
    }
}
