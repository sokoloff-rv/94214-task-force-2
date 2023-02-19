<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\RegistrationForm;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $registration = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $registration->load(Yii::$app->request->post());
            $registration->createUser();
        }

        return $this->render('index', ['registration' => $registration]);
    }
}
