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

    public function newResponse($taskId)
    {
        $response = new Response;
        $response->comment = $this->comment;
        $response->price = $this->price;
        $response->task_id = $taskId;
        $response->executor_id = Yii::$app->user->getId();
        return $response;
    }

    public function createResponse($taskId)
    {
        if ($this->validate()) {
            $newResponse = $this->newResponse($taskId);
            $newResponse->save(false);
            return true;
        }
        return false;
    }
}
