<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\Task;
use Taskforce\Models\Task as TaskBasic;

class TasksFilter extends Model
{
    const ONE_HOUR = '1 hour';
    const HALF_DAY = '12 hours';
    const ONE_DAY = '24 hours';

    public array $categories = [];
    public bool $distantWork = false;
    public bool $noResponse = false;
    public ?string $period = NULL;

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'distantWork' => 'Удаленная работа',
            'noResponse' => 'Без откликов',
            'period' => 'Период'
        ];
    }

    public function rules(): array
    {
        return [
            [['categories', 'distantWork', 'noResponse', 'period'], 'safe'],
        ];
    }

    public static function getPeriodsMap(): array
    {
        return [
            self::ONE_HOUR => '1 час',
            self::HALF_DAY => '12 часов',
            self::ONE_DAY => '24 часа'
        ];
    }
}
