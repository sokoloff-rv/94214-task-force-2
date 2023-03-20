<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\TaskSearch;

class MyTasksController extends SecuredController
{
    private $user;
    private $TaskSearch;

    public function __construct($id, $module)
    {
        $this->user = User::getCurrentUser();
        $this->TaskSearch = new TaskSearch();
        parent::__construct($id, $module);
    }

    public function actionIndex(): \yii\web\Response
    {
        if ($this->user->role === User::ROLE_CUSTOMER) {
            return Yii::$app->response->redirect(["/my-tasks/new"]);
        } elseif ($this->user->role === User::ROLE_EXECUTOR) {
            return Yii::$app->response->redirect(["/my-tasks/active"]);
        }
    }

    public function actionNew(): string
    {
        $result = $this->TaskSearch->getUserNewTasks($this->user->id);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionWorking(): string
    {
        $result = $this->TaskSearch->getUserWorkingTasks($this->user->id);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionClosed(): ?string
    {
        $result = $this->TaskSearch->getUserClosedTasks($this->user->id);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionActive(): string
    {
        $result = $this->TaskSearch->getUserActiveTasks($this->user->id);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionOverdue(): string
    {
        $result = $this->TaskSearch->getUserOverdueTasks($this->user->id);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function actionFinished(): string
    {
        $result = $this->TaskSearch->getUserFinishedTasks($this->user->id);

        return $this->render('index', [
            'tasks' => $result['tasks'],
            'pagination' => $result['pagination'],
        ]);
    }
}
