<?php
namespace app\models\forms;

use Yii;
use app\models\Task;
use app\models\Category;
use yii\base\Model;
use Taskforce\Models\Task as TaskBasic;

class NewTaskForm extends Model
{
    public string $title = '';
    public string $description = '';
    public string $category = '';
    public string $location = '';
    public string $budget = '';
    public string $deadline = '';
    public string $files = '';

    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category' => 'Категория',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'deadline' => 'Срок исполнения',
            'files' => 'Файлы'
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'description'], 'required'],
            [['category'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
            ['budget', 'integer', 'min' => 1],
            [['deadline'], 'date', 'format' => 'php:Y-m-d'],
            [['deadline'], 'compare', 'compareValue' => date('Y-m-d'),
            'operator' => '>', 'type' => 'date',
            'message' => 'Срок выполнения не может быть в прошлом'],
            [['files'], 'file'],
        ];
    }

    public function newTask()
    {
        $task = new Task;
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category;
        $task->budget = $this->budget;
        $task->deadline = $this->deadline;
        $task->status = TaskBasic::STATUS_NEW;
        $task->customer_id = Yii::$app->user->getId();
        return $task;
    }

    public function createTask()
    {
        if ($this->validate()) {
            $newTask = $this->newTask();
            $newTask->save(false);
            Yii::$app->response->redirect(["/tasks/view/$newTask->id"]);
        }
    }
}
