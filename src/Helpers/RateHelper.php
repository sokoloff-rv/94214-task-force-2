<?php
namespace Taskforce\Helpers;

class RateHelper
{
    public static function getStars($count): string
    {
        $stars = '';
        for($i = 0; $i < $count; $i++) {
            $stars .= "<span class=\"fill-star\">&nbsp;</span>";
        }
        for($i = 0; $i < 5 - $count; $i++) {
            $stars .= "<span>&nbsp;</span>";
        }
        return $stars;
    }
}
