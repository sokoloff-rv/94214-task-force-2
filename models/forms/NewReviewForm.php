<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use app\models\Review;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;

class NewReviewForm extends Model
{
    public string $comment = '';
    public string $grade = '';

    public function attributeLabels(): array
    {
        return [
            'comment' => 'Ваш комментарий',
            'grade' => 'Оценка работы',
        ];
    }

    public function rules(): array
    {
        return [
            [['comment', 'grade'], 'required'],
            [['grade'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            [['grade'], 'compare', 'compareValue' => 5, 'operator' => '<=', 'type' => 'number'],
        ];
    }

    public function newReview(int $taskId, int $executorId): Review
    {
        $review = new Review;
        $review->comment = $this->comment;
        $review->grade = $this->grade;
        $review->task_id = $taskId;
        $review->customer_id = Yii::$app->user->getId();
        $review->executor_id = $executorId;
        return $review;
    }

    public function createReview(int $taskId, int $executorId): bool
    {
        if ($this->validate()) {
            $newReview = $this->newReview($taskId, $executorId);
            $newReview->save(false);

            $task = Task::findOne($taskId);
            $task->status = TaskBasic::STATUS_COMPLETED;
            if (!$task->save()) {
                throw new BadRequestHttpException("Не получилось сохранить данные!");
            }
            return true;
        }
        return false;
    }
}
