<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\VkUser;

class AuthController extends NotSecuredController
{
    public function actionVk(): void
    {
        $url = Yii::$app->authClientCollection->getClient("vkontakte")->buildAuthUrl();
        Yii::$app->getResponse()->redirect($url);
    }

    public function actionLogin(): \yii\web\Response
    {
        $client = Yii::$app->authClientCollection->getClient("vkontakte");
        $code = Yii::$app->request->get('code');

        $accessToken = $client->fetchAccessToken($code);
        $userAttributes = $client->getUserAttributes();

        $foundUser = User::findOne(['vk_id' => $userAttributes['user_id']]);
        if ($foundUser) {
            Yii::$app->user->login($foundUser);
            return Yii::$app->response->redirect(['tasks']);
        }

        $vkUser = new VkUser();
        $vkUser->createUser($userAttributes);

        return Yii::$app->response->redirect(['tasks']);
    }
}
