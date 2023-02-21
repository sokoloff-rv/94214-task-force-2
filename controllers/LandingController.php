<?php
namespace app\controllers;

use yii\web\Controller;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
