<?php
namespace app\controllers;

use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
