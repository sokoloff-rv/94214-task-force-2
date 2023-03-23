<?php

namespace app\controllers;

use app\models\forms\EditProfileForm;
use app\models\forms\SecureProfileForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер для работы с пользователями.
 */
class UsersController extends SecuredController
{
    /**
     * Просмотр профиля пользователя.
     *
     * @param int $id Идентификатор пользователя.
     * @return string Рендер страницы пользователя.
     * @throws NotFoundHttpException Если пользователь не найден.
     */
    public function actionView(int $id): string
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Нет пользователя с id $id!");
        }

        // Условие ниже необходимо для соответствия сайта условиям ТЗ. Мне лично сама логика такого решения кажется неправильной, поэтому я реализовал её, чтобы выполнить условия ТЗ, но если её убрать, то будет лучше. В своем проекте я позаботился о том, чтобы на странице профиля заказчика не отображались данные, которые присущи только исполнителям. Так, на странице заказчика не отображается информация о рейтинге и блок "Статистика исполнителя". Также не отображаются специализации, потому что у заказчика нет возможности их выбрать.
        if ($user->role !== User::ROLE_EXECUTOR) {
            throw new NotFoundHttpException("Этот пользователь не является исполнителем, вы не можете просматривать его профиль!");
        }

        return $this->render('view', ['user' => $user]);
    }

    /**
     * Редактирование профиля пользователя.
     *
     * @return string|\yii\web\Response Рендер страницы редактирования профиля или обновление страницы.
     */
    public function actionEdit(): string | \yii\web\Response
    {
        $profileForm = new EditProfileForm();
        $user = User::getCurrentUser();

        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();

            if ($user->role === User::ROLE_EXECUTOR) {
                $specializations = $post['EditProfileForm']['specializations'];
                if (isset($specializations) && is_string($specializations)) {
                    $post['EditProfileForm']['specializations'] = explode(',', $specializations);
                }
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

    /**
     * Изменение настроек безопасности профиля пользователя.
     *
     * @return string|\yii\web\Response Рендер страницы безопасности или обновление страницы.
     */
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

    /**
     * Выход из аккаунта пользователя.
     *
     * @return Response Редирект на главную страницу.
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
