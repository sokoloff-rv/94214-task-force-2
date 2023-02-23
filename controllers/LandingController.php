<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use app\models\forms\LoginForm;

class LandingController extends NotSecuredController
{
    public $layout = 'landing';

    public function actionIndex()
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return Yii::$app->response->redirect(['tasks']);
            }
        }

        return $this->render('index', ['login' => $loginForm]);
    }
}
