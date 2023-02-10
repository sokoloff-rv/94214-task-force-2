<?php
namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;
use app\models\Task;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $query = Task::find();
        $query->where(['status' => Task::STATUS_NEW])
        ->orderBy(['creation_date' => SORT_DESC])
        ->with('category')
        ->with('city');
        $tasks = $query->all();
        return $this->render('index', ['tasks' => $tasks]);
    }
}
