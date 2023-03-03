<?php

namespace app\controllers;

use app\models\forms\NewTaskForm;
use app\models\forms\TasksFilter;
use app\models\Task;
use app\models\TaskSearch;
use app\models\User;
use app\models\Response;
use Yii;
use yii\web\NotFoundHttpException;
use Taskforce\Models\Task as TaskBasic;

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

    public function actionIndex(): string
    {
        $TaskSearch = new TaskSearch();
        $result = $TaskSearch->getTasks();
        $filter = new TasksFilter();

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
            'filter' => $filter,
        ]);
    }

    public function actionView(int $id): string
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $id!");
        }
        return $this->render('view', ['task' => $task]);
    }

    public function actionNew(): \yii\web\Response | string
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

    public function actionAccept(int $responseId, int $taskId, int $executorId): \yii\web\Response
    {
        $response = Response::findOne($responseId);
        $response->status = Response::STATUS_ACCEPTED;
        $response->save();

        $task = Task::findOne($taskId);
        $task->status = TaskBasic::STATUS_WORKING;
        $task->executor_id = $executorId;
        $task->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRefuse(int $responseId): \yii\web\Response
    {
        $response = Response::findOne($responseId);
        $response->status = Response::STATUS_REJECTED;
        $response->save();

        return $this->redirect(Yii::$app->request->referrer);
    }
}
