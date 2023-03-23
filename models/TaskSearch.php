<?php

namespace app\models;

use app\models\forms\TasksFilter;
use app\models\Response;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;
use Yii;
use yii\base\Model;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Класс TaskSearch предназначен для поиска и фильтрации задач.
 */
class TaskSearch extends Model
{
    /**
     * Возвращает список задач, удовлетворяющих заданным условиям.
     *
     * @return array Массив с задачами и информацией о пагинации.
     */
    public function getTasks(?int $category = null): array
    {
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_NEW])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        if ($category) {
            $tasks = $tasks->andWhere(['category_id' => $category]);
        }

        $request = Yii::$app->getRequest();

        if ($request->get('TasksFilter')) {
            $categories = $request->get('TasksFilter')['categories'];
            $distantWork = $request->get('TasksFilter')['distantWork'];
            $noResponse = $request->get('TasksFilter')['noResponse'];
            $period = $request->get('TasksFilter')['period'];

            if ($categories) {
                $tasks = $tasks->andWhere(['in', 'category_id', $categories]);
            }

            if ($distantWork) {
                $tasks = $tasks->andWhere(['city_id' => null]);
            }

            if ($noResponse) {
                $tasksWithResponse = Response::find()
                    ->select(['task_id', 'id'])
                    ->all();
                $tasksWithResponse = ArrayHelper::map($tasksWithResponse, 'id', 'task_id');
                $tasks = $tasks->andWhere(['not in', 'id', $tasksWithResponse]);
            }

            if ($period !== TasksFilter::ALL_TIME) {
                $tasks = $tasks->andWhere(['>', 'creation_date', new Expression("CURRENT_TIMESTAMP() - INTERVAL $period")]);
            }
        }

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 5,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    /**
     * Возвращает список задач для пользователя, удовлетворяющих заданным условиям.
     *
     * @param int $userId ID пользователя.
     * @param string $role Роль пользователя (исполнитель или заказчик).
     * @param array $statuses Массив с допустимыми статусами задач.
     * @param bool $isOverdue Флаг для фильтрации просроченных задач.
     * @return array Массив с задачами и информацией о пагинации.
     */
    public function getUserTasks(int $userId, string $role, array $statuses, bool $isOverdue = false): array
    {
        $tasks = Task::find()
            ->andWhere(['in', 'status', $statuses])
            ->andWhere([$role . '_id' => $userId])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        if ($isOverdue) {
            $tasks = $tasks->andWhere(['<', 'deadline', new Expression('CURRENT_TIMESTAMP()')]);
        }

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 5,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }
}
