<?php

namespace app\models;

use app\models\forms\TasksFilter;
use app\models\Response;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;
use Yii;
use yii\data\Pagination;
use yii\base\Model;
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

        $count = $tasks->count();

        $pagination = new Pagination([
            'totalCount' => $count,
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
