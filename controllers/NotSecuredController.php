<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Абстрактный контроллер для обработки действий, доступных только незарегистрированным пользователям.
 */
abstract class NotSecuredController extends Controller
{
    /**
     * Определяет правила доступа для незарегистрированных пользователей.
     *
     * @return array Массив с настройками поведения.
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    return $this->redirect('/tasks');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }
}
