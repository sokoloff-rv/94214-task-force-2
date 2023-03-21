<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\TaskSearch;
use Taskforce\Models\Task as TaskBasic;
use yii\base\Module;
use yii\web\Response;

class MyTasksController extends SecuredController
{
    private User $user;
    private TaskSearch $TaskSearch;

    public function __construct(string $id, Module $module)
    {
        $this->user = User::getCurrentUser();
        $this->TaskSearch = new TaskSearch();
        parent::__construct($id, $module);
    }

    public function actionIndex(): Response
    {
        if ($this->user->role === User::ROLE_CUSTOMER) {
            return Yii::$app->response->redirect(["/my-tasks/new"]);
        } elseif ($this->user->role === User::ROLE_EXECUTOR) {
            return Yii::$app->response->redirect(["/my-tasks/working"]);
        }
    }

    public function actionNew(): ?string
    {
        $result = $this->TaskSearch->getUserTasks($this->user->id, $this->user->role, [
            TaskBasic::STATUS_NEW,
        ]);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionWorking(): ?string
    {
        $result = $this->TaskSearch->getUserTasks($this->user->id, $this->user->role, [
            TaskBasic::STATUS_WORKING,
        ]);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionClosed(): ?string
    {
        $result = $this->TaskSearch->getUserTasks($this->user->id, $this->user->role, [
            TaskBasic::STATUS_CANCELLED,
            TaskBasic::STATUS_COMPLETED,
            TaskBasic::STATUS_FAILED,
        ]);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionOverdue(): ?string
    {
        $isOverdue = true;
        $result = $this->TaskSearch->getUserTasks($this->user->id, $this->user->role, [
            TaskBasic::STATUS_WORKING,
        ], $isOverdue);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }
}
