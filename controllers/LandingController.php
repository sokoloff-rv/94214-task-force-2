<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\forms\LoginForm;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function actionIndex(): string
    {
        $login = new LoginForm();
        return $this->render('index', ['login' => $login]);
    }
}
