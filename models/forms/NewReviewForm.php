<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use app\models\Review;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;

/**
 * Класс формы добавления нового отзыва.
 */
class NewReviewForm extends Model
{
    public string $comment = '';
    public string $grade = '';

    /**
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
     */
    public function attributeLabels(): array
    {
        return [
            'comment' => 'Ваш комментарий',
            'grade' => 'Оценка работы',
        ];
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['comment', 'grade'], 'required'],
            [['grade'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            [['grade'], 'compare', 'compareValue' => 5, 'operator' => '<=', 'type' => 'number'],
            [['comment', 'grade'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * Создает новый объект отзыва на основе данных формы.
     *
     * @param int $taskId ID задачи.
     * @param int $executorId ID исполнителя.
     * @return Review Новый объект отзыва.
     */
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

    /**
     * Создает и сохраняет новый отзыв, основанный на данных формы.
     *
     * @param int $taskId ID задачи.
     * @param int $executorId ID исполнителя.
     * @return bool Возвращает true, если отзыв успешно создан и сохранен, иначе false.
     * @throws BadRequestHttpException В случае ошибки при сохранении задачи.
     */
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
