<?php

namespace app\controllers;

use app\models\forms\NewResponseForm;
use app\models\forms\NewReviewForm;
use app\models\forms\NewTaskForm;
use app\models\forms\TasksFilter;
use app\models\Response;
use app\models\Task;
use app\models\TaskSearch;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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

    public function actionView(int $id): \yii\web\Response | string
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $id!");
        }

        $responseForm = new NewResponseForm();
        $reviewForm = new NewReviewForm();

        return $this->render('view', ['task' => $task, 'responseForm' => $responseForm, 'reviewForm' => $reviewForm]);
    }

    public function actionResponse(int $taskId): \yii\web\Response
    {
        $responseForm = new NewResponseForm();
        if (Yii::$app->request->getIsPost()) {
            $responseForm->load(Yii::$app->request->post());
            if (!$responseForm->createResponse($taskId)) {
                throw new ServerErrorHttpException("Не получилось создать отклик для задания с id $taskId!");
            }
            return Yii::$app->response->redirect(["/tasks/view/$taskId"]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionReview(int $taskId, int $executorId): \yii\web\Response
    {
        $reviewForm = new NewReviewForm();
        if (Yii::$app->request->getIsPost()) {

            $reviewForm->load(Yii::$app->request->post());
            if (!$reviewForm->createReview($taskId, $executorId)) {
                throw new ServerErrorHttpException("Не получилось создать отзыв на пользователя по заданию с id $taskId!");
            } else {
                $user = User::findOne($executorId);
                if (!$user) {
                    throw new NotFoundHttpException("Нет пользователя с id $executorId!");
                }
                if (!$user->increaseCounterCompletedTasks()) {
                    throw new ServerErrorHttpException("Не получилось сохранить данные!");
                }
            }

            return Yii::$app->response->redirect(["/tasks/view/$taskId"]);
        }

        return $this->redirect(Yii::$app->request->referrer);
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
        if (!$response) {
            throw new NotFoundHttpException("Нет отклика с id $responseId!");
        }
        if (!$response->accept()) {
            throw new ServerErrorHttpException("Не получилось сохранить данные!");
        }

        $task = Task::findOne($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $taskId!");
        }
        if (!$task->startWorking($executorId)) {
            throw new ServerErrorHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRefuse(int $responseId): \yii\web\Response
    {
        $response = Response::findOne($responseId);
        if (!$response) {
            throw new NotFoundHttpException("Нет отклика с id $responseId!");
        }
        if (!$response->reject()) {
            throw new ServerErrorHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionFail(int $taskId, int $executorId): \yii\web\Response
    {
        $task = Task::findOne($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $taskId!");
        }
        if (!$task->failTask()) {
            throw new ServerErrorHttpException("Не получилось сохранить данные!");
        }

        $user = User::findOne($executorId);
        if (!$user) {
            throw new NotFoundHttpException("Нет пользователя с id $executorId!");
        }
        if (!$user->increaseCounterFailedTasks()) {
            throw new ServerErrorHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCancel(int $taskId): \yii\web\Response
    {
        $task = Task::findOne($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $taskId!");
        }
        if (!$task->cancelTask()) {
            throw new ServerErrorHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
