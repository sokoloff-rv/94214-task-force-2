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

class TaskSearch extends Model
{
    public function getTasks(): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_NEW])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

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
            'pageSize' => 10,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    public function getUserNewTasks($userId): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_NEW])
            ->andWhere(['customer_id' => $userId])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 10,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    public function getUserWorkingTasks($userId): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_WORKING])
            ->andWhere(['customer_id' => $userId])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 10,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    public function getUserClosedTasks($userId): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->andWhere(['in', 'status', [
                TaskBasic::STATUS_CANCELLED,
                TaskBasic::STATUS_COMPLETED,
                TaskBasic::STATUS_FAILED,
            ]])
            ->andWhere(['customer_id' => $userId])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 10,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    public function getUserActiveTasks($userId): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_WORKING])
            ->andWhere(['executor_id' => $userId])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 10,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    public function getUserOverdueTasks($userId): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_WORKING])
            ->andWhere(['executor_id' => $userId])
            ->andWhere(['<', 'deadline', new Expression('CURRENT_TIMESTAMP()')])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 10,
        ]);

        $tasks = $tasks->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'tasks' => $tasks,
            'pagination' => $pagination,
        ];
    }

    public function getUserFinishedTasks($userId): array
    {
        /** @var ActiveQuery $tasks */
        $tasks = Task::find()
            ->andWhere(['in', 'status', [
                TaskBasic::STATUS_COMPLETED,
                TaskBasic::STATUS_FAILED,
            ]])
            ->andWhere(['executor_id' => $userId])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $pagination = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 10,
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
