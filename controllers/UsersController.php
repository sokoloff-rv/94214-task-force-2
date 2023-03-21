<?php

namespace app\controllers;

use app\models\forms\EditProfileForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    public function actionView(int $id): string
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Нет пользователя с id $id!");
        }
        return $this->render('view', ['user' => $user]);
    }

    public function actionEdit(): string
    {
        $profile = new EditProfileForm();
        $user = User::getCurrentUser();
        return $this->render('edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function actionSecure(): string
    {
        $user = User::getCurrentUser();
        return $this->render('secure', ['user' => $user]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
