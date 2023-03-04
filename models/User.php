<?php

namespace app\models;

use Taskforce\Models\Task as TaskBasic;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $information
 * @property string|null $specializations
 * @property string|null $avatar
 * @property string|null $register_date
 * @property string $role
 * @property int|null $succesful_tasks
 * @property int|null $failed_tasks
 * @property int|null $city_id
 *
 * @property Cities $city
 * @property Responses[] $responses
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_CUSTOMER = 'customer';
    const ROLE_EXECUTOR = 'executor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'role'], 'required'],
            [['birthday', 'register_date'], 'safe'],
            [['information', 'role'], 'string'],
            [['succesful_tasks', 'failed_tasks', 'city_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['email', 'password', 'phone', 'telegram'], 'string', 'max' => 100],
            [['specializations', 'avatar'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'information' => 'Information',
            'specializations' => 'Specializations',
            'avatar' => 'Avatar',
            'register_date' => 'Register Date',
            'role' => 'Role',
            'succesful_tasks' => 'Succesful Tasks',
            'failed_tasks' => 'Failed Tasks',
            'city_id' => 'City ID',
        ];
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
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerReviews()
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ReviewsOnExecutor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsOnExecutor()
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ExecutorTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorTasks()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    public static function getCurrentUser(): User
    {
        return User::findOne(Yii::$app->user->getId());
    }

    public function getUserRating(): string
    {
        $sumOfGrades = 0;
        $reviews = $this->reviewsOnExecutor;

        foreach ($reviews as $review) {
            $sumOfGrades += $review['grade'];
        }

        if ($sumOfGrades > 0) {
            $rate = round($sumOfGrades / (count($reviews) + $this->failed_tasks), 2);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    public function increaseCounterCompletedTasks(): bool
    {
        $this->succesful_tasks += 1;
        return $this->save();
    }

    public function getUserStatus(): string
    {
        if (
            Task::findOne(['executor_id' => $this->id, 'status' => TaskBasic::STATUS_WORKING])
        ) {
            return 'Занят';
        }
        return 'Открыт для новых заказов';
    }

    public static function findIdentity($id): ?User
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?User
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): ?string
    {
        return null;
    }

    public function validateAuthKey($authKey): bool
    {
        return false;
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

}
