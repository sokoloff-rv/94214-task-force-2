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

        if (Yii::$app->request->getIsPost()) {
            $registration->load(Yii::$app->request->post());

            if ($registration->validate()) {
                $registration->password = Yii::$app->security->generatePasswordHash($registration->password);

                $registration->newUser()->save(false);
                Yii::$app->response->redirect(['tasks']);
            }
        }

        return $this->render('index', ['registration' => $registration]);
    }
}
