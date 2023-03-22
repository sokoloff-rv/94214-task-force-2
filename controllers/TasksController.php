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
use yii\web\BadRequestHttpException;

/**
 * Контроллер для работы с заданиями.
 */
class TasksController extends SecuredController
{
    /**
     * Определение правил доступа.
     *
     * @return array Массив с настройками поведения.
     */
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

    /**
     * Отображение списка заданий.
     *
     * @return string Рендер страницы.
     */
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

    /**
     * Отображение задания по идентификатору.
     *
     * @param int $id Идентификатор задания.
     * @return \yii\web\Response|string Рендер страницы или редирект.
     * @throws NotFoundHttpException Если задание не найдено.
     */
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

    /**
     * Создание отклика на задание.
     *
     * @param int $taskId Идентификатор задания.
     * @return \yii\web\Response Редирект.
     * @throws BadRequestHttpException Если не удалось создать отклик.
     */
    public function actionResponse(int $taskId): \yii\web\Response
    {
        $responseForm = new NewResponseForm();
        if (Yii::$app->request->getIsPost()) {
            $responseForm->load(Yii::$app->request->post());
            if (!$responseForm->createResponse($taskId)) {
                throw new BadRequestHttpException("Не получилось создать отклик для задания с id $taskId!");
            }
            return Yii::$app->response->redirect(["/tasks/view/$taskId"]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Создание отзыва на задание.
     *
     * @param int $taskId Идентификатор задания.
     * @param int $executorId Идентификатор исполнителя.
     * @return \yii\web\Response Редирект.
     * @throws BadRequestHttpException Если не удалось создать отзыв.
     * @throws NotFoundHttpException Если пользователь не найден.
     */
    public function actionReview(int $taskId, int $executorId): \yii\web\Response
    {
        $reviewForm = new NewReviewForm();
        if (Yii::$app->request->getIsPost()) {

            $reviewForm->load(Yii::$app->request->post());
            if (!$reviewForm->createReview($taskId, $executorId)) {
                throw new BadRequestHttpException("Не получилось создать отзыв на пользователя по заданию с id $taskId!");
            } else {
                $user = User::findOne($executorId);
                if (!$user) {
                    throw new NotFoundHttpException("Нет пользователя с id $executorId!");
                }
                if (!$user->increaseCounterCompletedTasks()) {
                    throw new BadRequestHttpException("Не получилось сохранить данные!");
                }
            }

            return Yii::$app->response->redirect(["/tasks/view/$taskId"]);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Создание нового задания.
     *
     * @return \yii\web\Response|string Рендер страницы или редирект.
     */
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

    /**
     * Принятие отклика на задание.
     *
     * @param int $responseId Идентификатор отклика.
     * @param int $taskId Идентификатор задания.
     * @param int $executorId Идентификатор исполнителя.
     * @return \yii\web\Response Редирект.
     * @throws NotFoundHttpException Если отклик или задание не найдены.
     * @throws BadRequestHttpException Если не удалось сохранить данные.
     */
    public function actionAccept(int $responseId, int $taskId, int $executorId): \yii\web\Response
    {
        $response = Response::findOne($responseId);
        if (!$response) {
            throw new NotFoundHttpException("Нет отклика с id $responseId!");
        }
        if (!$response->accept()) {
            throw new BadRequestHttpException("Не получилось сохранить данные!");
        }

        $task = Task::findOne($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $taskId!");
        }
        if (!$task->startWorking($executorId)) {
            throw new BadRequestHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Отклонение отклика на задание.
     *
     * @param int $responseId Идентификатор отклика.
     * @return \yii\web\Response Редирект.
     * @throws NotFoundHttpException Если отклик не найден.
     * @throws BadRequestHttpException Если не удалось сохранить данные.
     */
    public function actionRefuse(int $responseId): \yii\web\Response
    {
        $response = Response::findOne($responseId);
        if (!$response) {
            throw new NotFoundHttpException("Нет отклика с id $responseId!");
        }
        if (!$response->reject()) {
            throw new BadRequestHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Провал задания.
     *
     * @param int $taskId Идентификатор задания.
     * @param int $executorId Идентификатор исполнителя.
     * @return \yii\web\Response Редирект.
     * @throws NotFoundHttpException Если задание или пользователь не найдены.
     * @throws BadRequestHttpException Если не удалось сохранить данные.
     */
    public function actionFail(int $taskId, int $executorId): \yii\web\Response
    {
        $task = Task::findOne($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $taskId!");
        }
        if (!$task->failTask()) {
            throw new BadRequestHttpException("Не получилось сохранить данные!");
        }

        $user = User::findOne($executorId);
        if (!$user) {
            throw new NotFoundHttpException("Нет пользователя с id $executorId!");
        }
        if (!$user->increaseCounterFailedTasks()) {
            throw new BadRequestHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Отмена задания.
     *
     * @param int $taskId Идентификатор задания.
     * @return \yii\web\Response Редирект.
     * @throws NotFoundHttpException Если задание не найдено.
     * @throws BadRequestHttpException Если не удалось сохранить данные.
     */
    public function actionCancel(int $taskId): \yii\web\Response
    {
        $task = Task::findOne($taskId);
        if (!$task) {
            throw new NotFoundHttpException("Нет задания с id $taskId!");
        }
        if (!$task->cancelTask()) {
            throw new BadRequestHttpException("Не получилось сохранить данные!");
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
