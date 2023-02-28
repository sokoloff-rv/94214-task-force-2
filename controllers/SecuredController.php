<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class SecuredController extends Controller
{
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
