<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use Taskforce\Models\Task as TaskBasic;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $title
 * @property string|null $description
 * @property int $category_id
 * @property int|null $city_id
 * @property string|null $budget
 * @property string|null $deadline
 * @property string|null $creation_date
 * @property string $status
 * @property int|null $executor_id
 *
 * @property Categories $category
 * @property Cities $city
 * @property Users $customer
 * @property Users $executor
 * @property Files[] $files
 * @property Responses[] $responses
 * @property Reviews[] $reviews
 */
class Task extends \yii\db\ActiveRecord
{
    public function startWorking(int $executorId): bool
    {
        $this->status = TaskBasic::STATUS_WORKING;
        $this->executor_id = $executorId;
        return $this->save();
    }

    public function failTask(): bool
    {
        $this->status = TaskBasic::STATUS_FAILED;
        return $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'title', 'category_id'], 'required'],
            [['customer_id', 'category_id', 'city_id', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['deadline', 'creation_date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['budget'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
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
            'creation_date' => 'Creation Date',
            'status' => 'Status',
            'executor_id' => 'Executor ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }
}
