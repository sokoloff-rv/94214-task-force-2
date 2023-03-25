<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Абстрактный контроллер для обработки действий, доступных только авторизованным пользователям.
 */
abstract class SecuredController extends Controller
{
    /**
     * Определяет правила доступа для авторизованных пользователей.
     *
     * @return array Массив с настройками поведения.
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    return $this->redirect('/landing');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
