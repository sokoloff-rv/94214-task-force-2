<?php
namespace app\controllers;

use Yii;
use app\models\forms\TasksFilter;
use app\models\forms\NewTaskForm;
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

    public function actionView($id)
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $id!");
        }
        return $this->render('view', ['task' => $task]);
    }

    public function actionNew()
    {
        $taskForm = new NewTaskForm();

        if (Yii::$app->request->getIsPost()) {
            $taskForm->load(Yii::$app->request->post());
            $taskForm->createTask();
        }

        return $this->render('new', ['newTask' => $taskForm]);
    }
}
