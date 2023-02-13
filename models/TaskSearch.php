<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\models\Response;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;

class TaskSearch extends Model
{
    public function getTasks(): array
    {
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
        }

        if (isset($categories) && $categories) {
            $tasks = $tasks->andWhere(['in', 'category_id', $categories]);
        }

        if (isset($distantWork) && $distantWork) {
            $tasks = $tasks->andWhere(['city_id' => null]);
        }

        if (isset($noResponse) && $noResponse) {
            $tasksWithResponse = Response::find()
                ->select(['task_id', 'id'])
                ->all();
            $tasksWithResponse = ArrayHelper::map($tasksWithResponse, 'id', 'task_id');
            $tasks = $tasks->andWhere(['not in', 'id', $tasksWithResponse]);
        }

        if (isset($period) && $period !== 'ALL TIME') {
            $tasks = $tasks->andWhere(['>', 'creation_date', new Expression("CURRENT_TIMESTAMP() - INTERVAL $period")]);
        }

        $tasks = $tasks->all();

        return $tasks;
    }
}
