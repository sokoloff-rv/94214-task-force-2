<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use Yii;

class RegistrationController extends NotSecuredController
{
    public function actionIndex(): string
    {
        $registration = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $registration->load(Yii::$app->request->post());
            $registration->createUser();
        }

        return $this->render('index', ['registration' => $registration]);
    }
}
