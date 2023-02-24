<?php
namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    public function actionView($id): string
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Нет пользователя с id $id!");
        }
        return $this->render('view', ['user' => $user]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
