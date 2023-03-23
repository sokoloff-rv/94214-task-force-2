<?php

namespace app\validators;

use app\models\City;
use Taskforce\Utils\Geocoder;
use yii\validators\Validator;

/**
 * Класс LocationValidator представляет валидатор для проверки наличия города в базе данных.
 */
class LocationValidator extends Validator
{
    /**
     * Валидация значения.
     *
     * @param mixed $value Значение для валидации.
     * @return array|null Массив с ошибками или null, если валидация прошла успешно.
     */
    protected function validateValue($value)
    {
        $cityName = Geocoder::getLocationData($value, 'city');
        if (City::findOne(['name' => $cityName])) {
            return null;
        } else {
            return ["Города $cityName нет в нашей базе!", []];
        }
    }
}
