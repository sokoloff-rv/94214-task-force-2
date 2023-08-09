<?php

namespace app\models;

use Taskforce\Models\Task as TaskBasic;
use Yii;

/**
 * Класс модели для таблицы "tasks" в базе данных.
 *
 * @property int $id Идентификатор задачи.
 * @property int $customer_id Идентификатор заказчика.
 * @property string $title Заголовок задачи.
 * @property string|null $description Описание задачи.
 * @property int $category_id Идентификатор категории.
 * @property int|null $city_id Идентификатор города.
 * @property string|null $budget Бюджет задачи.
 * @property string|null $deadline Срок выполнения задачи.
 * @property string $location Местоположение задачи.
 * @property float|null $latitude Широта местоположения задачи.
 * @property float|null $longitude Долгота местоположения задачи.
 * @property string|null $creation_date Дата создания задачи.
 * @property string $status Статус задачи.
 * @property int|null $executor_id Идентификатор исполнителя.
 *
 * @property Category $category Категория задачи.
 * @property City $city Город, в котором размещена задача.
 * @property User $customer Заказчик задачи.
 * @property User $executor Исполнитель задачи.
 * @property File $files Файлы, прикрепленные к задаче.
 * @property Response $responses Отклики на задачу.
 * @property Review $reviews Отзывы о выполнении задачи.
 */
class Task extends \yii\db\ActiveRecord

{
    /**
     * Возвращает имя таблицы в базе данных.
     *
     * @return string Имя таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * Возвращает список правил валидации для атрибутов модели.
     *
     * @return array Список правил валидации.
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'title', 'category_id'], 'required'],
            [['customer_id', 'category_id', 'city_id', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['deadline', 'creation_date'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['title', 'location'], 'string', 'max' => 255],
            [['budget'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

     /**
     * Возвращает список меток атрибутов.
     *
     * @return array Список меток атрибутов.
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'budget' => 'Budget',
            'deadline' => 'Deadline',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'longitude',
            'creation_date' => 'Creation Date',
            'status' => 'Status',
            'executor_id' => 'Executor ID',
        ];
    }

    /**
     * Получает запрос для [[Category]].
     *
     * @return \yii\db\ActiveQuery Запрос для категории задачи.
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Получает запрос для [[City]].
     *
     * @return \yii\db\ActiveQuery Запрос для города, в котором размещена задача.
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Получает запрос для [[Customer]].
     *
     * @return \yii\db\ActiveQuery Запрос для пользователя-заказчика.
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Получает запрос для [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Получает запрос для [[Files]].
     *
     * @return \yii\db\ActiveQuery Запрос для файлов, прикрепленных к задаче.
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Получает запрос для [[Responses]].
     *
     * @return \yii\db\ActiveQuery Запрос для откликов на задачу.
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Получает запрос для [[Reviews]].
     *
     * @return \yii\db\ActiveQuery Запрос для отзывов о выполнении задачи.
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }

    /**
     * Начинает выполнение задачи и назначает исполнителя.
     *
     * @param int $executorId Идентификатор исполнителя.
     * @return bool Возвращает true, если задача успешно сохранена, иначе - false.
     */
    public function startWorking(int $executorId): bool
    {
        $this->status = TaskBasic::STATUS_WORKING;
        $this->executor_id = $executorId;
        return $this->save();
    }

    /**
     * Помечает задачу как проваленную.
     *
     * @return bool Возвращает true, если задача успешно сохранена, иначе - false.
     */
    public function failTask(): bool
    {
        $this->status = TaskBasic::STATUS_FAILED;
        return $this->save();
    }

    /**
     * Отменяет задачу.
     *
     * @return bool Возвращает true, если задача успешно сохранена, иначе - false.
     */
    public function cancelTask(): bool
    {
        $this->status = TaskBasic::STATUS_CANCELLED;
        return $this->save();
    }
}
