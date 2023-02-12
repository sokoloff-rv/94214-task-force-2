<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\models\forms\TasksFilter;
use app\models\Response;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_NEW])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city');

        $request = \Yii::$app->getRequest();
        $filterParams = $request->get('TasksFilter');

        if (isset($filterParams['categories']) && $filterParams['categories']) {
            $categories = $filterParams['categories'];

            $tasks = $tasks->andWhere(['in', 'category_id', $categories]);
        }

        if (isset($filterParams['distantWork']) && $filterParams['distantWork']) {
            $tasks = $tasks->andWhere(['city_id' => null]);
        }

        if (isset($filterParams['noResponse']) && $filterParams['noResponse']) {
            $tasksWithResponse = Response::find()
            ->select(['task_id', 'id'])
            ->all();
            $tasksWithResponse = ArrayHelper::map($tasksWithResponse, 'id', 'task_id');

            $tasks = $tasks->andWhere(['not in', 'id', $tasksWithResponse]);
        }

        if (isset($filterParams['period']) && $filterParams['period'] && $filterParams['period'] !== 'ALL TIME') {
            $period = $filterParams['period'];

            $tasks = $tasks->andWhere(['>', 'creation_date', new Expression("CURRENT_TIMESTAMP() - INTERVAL $period")]);
        }

        $tasks = $tasks->all();

        $filter = new TasksFilter();

        return $this->render('index', ['tasks' => $tasks, 'filter' => $filter]);
    }
}
