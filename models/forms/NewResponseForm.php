<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Response;

class NewResponseForm extends Model
{
    public string $comment = '';
    public string $price = '';

    public function attributeLabels(): array
    {
        return [
            'comment' => 'Ваш комментарий',
            'price' => 'Стоимость',
        ];
    }

    public function rules(): array
    {
        return [
            [['comment', 'price'], 'safe'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
        ];
    }

    public function newResponse($responseToTaskId)
    {
        $response = new Response;
        $response->comment = $this->comment;
        $response->price = $this->price;
        $response->task_id = $responseToTaskId;
        $response->executor_id = Yii::$app->user->getId();
        return $response;
    }

    public function createResponse($responseToTaskId)
    {
        if ($this->validate()) {
            $newResponse = $this->newResponse($responseToTaskId);
            $newResponse->save(false);
            return $responseToTaskId;
        }
        return false;
    }
}
