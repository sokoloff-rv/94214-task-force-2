<?php

namespace app\validators;

use app\models\City;
use Taskforce\Utils\Geocoder;
use yii\validators\Validator;

class LocationValidator extends Validator
{
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
