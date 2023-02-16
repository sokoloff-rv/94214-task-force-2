<?php
namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\TaskSearch;
use app\models\forms\TasksFilter;

class TasksController extends Controller
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
