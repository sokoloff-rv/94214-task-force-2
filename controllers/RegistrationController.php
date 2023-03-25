<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use Yii;

/**
 * Контроллер для обработки регистрации пользователя.
 */
class RegistrationController extends NotSecuredController
{
    /**
     * Обрабатывает запрос на регистрацию нового пользователя.
     *
     * @return string Результат рендеринга страницы.
     */
    public function actionIndex(): string
    {
        $registration = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $registration->load(Yii::$app->request->post());
            $registration->createUser();
            Yii::$app->response->redirect(['tasks']);
        }

        return $this->render('index', ['registration' => $registration]);
    }
}
