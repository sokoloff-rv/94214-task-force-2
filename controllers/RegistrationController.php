<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\Registration;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $registration = new Registration();

        return $this->render('index', ['registration' => $registration]);
    }
}
