<?php
namespace app\controllers;

use app\models\forms\TasksFilter;
use app\models\Task;
use app\models\TaskSearch;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
{
    public function actionIndex()
    {
        $TaskSearch = new TaskSearch();

        $tasks = $TaskSearch->getTasks();
        $filter = new TasksFilter();

        return $this->render('index', ['tasks' => $tasks, 'filter' => $filter]);
    }

    public function actionView($id): string
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $id!");
        }
        return $this->render('view', ['task' => $task]);
    }
}
