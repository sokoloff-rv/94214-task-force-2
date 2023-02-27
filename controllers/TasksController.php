<?php
namespace app\controllers;

use app\models\forms\NewTaskForm;
use app\models\forms\TasksFilter;
use app\models\Task;
use app\models\TaskSearch;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
{
    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['new'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->getIdentity()->role === User::ROLE_EXECUTOR;
            },
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

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
            $newTaskId = $taskForm->createTask();
            if ($newTaskId) {
                return Yii::$app->response->redirect(["/tasks/view/$newTaskId"]);
            }
        }

        return $this->render('new', ['newTask' => $taskForm]);
    }
}
