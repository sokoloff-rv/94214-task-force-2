<?php

namespace app\controllers;

use app\models\forms\EditProfileForm;
use app\models\forms\SecureProfileForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    public function actionEdit(): string | \yii\web\Response
    {
        $profileForm = new EditProfileForm();
        $user = User::getCurrentUser();

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();

            $specializations = $post['EditProfileForm']['specializations'];
            if (isset($specializations) && is_string($specializations)) {
                $post['EditProfileForm']['specializations'] = explode(',', $specializations);
            }

            if ($profileForm->load($post) && $profileForm->validate()) {
                $profileForm->saveProfile($user->id);
                return $this->refresh();
            }
        }

        return $this->render('edit', [
            'user' => $user,
            'profile' => $profileForm,
        ]);
    }

    public function actionSecure(): string | \yii\web\Response
    {
        $secureForm = new SecureProfileForm();
        $user = User::getCurrentUser();

        $secureForm->hiddenContacts = (bool) $user->hidden_contacts;

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $secureForm->load($post);
            if ($secureForm->load($post) && $secureForm->validate()) {
                $secureForm->saveProfile($user->id);
                return $this->refresh();
            }
        }

        return $this->render('secure', [
            'user' => $user,
            'secure' => $secureForm,
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
