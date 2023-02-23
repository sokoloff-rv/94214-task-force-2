<?php
namespace app\controllers;

use Yii;
use app\models\forms\RegistrationForm;

class RegistrationController extends NotSecuredController
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
