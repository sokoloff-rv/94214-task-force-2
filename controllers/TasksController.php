<?php
namespace app\controllers;

use app\models\Task;
use Taskforce\Models\Task as TaskBasic;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\forms\TasksFilter;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->where(['status' => TaskBasic::STATUS_NEW])
            ->orderBy(['creation_date' => SORT_DESC])
            ->with('category')
            ->with('city')
            ->all();

        $filter = new TasksFilter();

        return $this->render('index', ['tasks' => $tasks, 'filter' => $filter]);
    }
}
